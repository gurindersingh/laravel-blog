<?php

namespace Gurinder\LaravelBlog\Http\Controllers;


use Illuminate\Http\Request;
use Gurinder\LaravelBlog\Models\Tag;
use Gurinder\LaravelBlog\Models\Category;

class TaxonomiesController extends Controller
{

    public function index($type = null)
    {
        if (!authUserHasPermission('manage-taxonomies')) {
            abort(403, "User does not have permissions to manage taxonomies");
        };

        $categories = Category::whereParentId(null)->with('children')->get();

        $tags = Tag::all();

        return view('gblog::taxonomies.index', compact('categories', 'tags'));
    }

    public function create()
    {
        return view('gblog::taxonomies.create');
    }

    public function store(Request $request)
    {
        list($taxonomyType, $model) = $this->getModel($request);

        $slug = str_slug($request->label);

        $data = $request->validate([
            'label'       => [
                'required',
                'unique:' . str_plural($taxonomyType) . ',label',
                function ($attribute, $value, $fail) use ($slug, $model) {
                    if ($model::whereSlug($slug)->exists()) {
                        return $fail("Choose Different name, its already been used");
                    }
                    return $value;
                }
            ],
            'description' => 'nullable',
            'parent_id'   => 'nullable|exists:' . str_plural($taxonomyType) . ',id'
        ]);

        $parentId = $data['parent_id'];

        $data = [
            'label'       => $data['label'],
            'slug'        => $slug,
            'description' => $data['description'],
        ];

        $data = ($taxonomyType == 'category') ? $data + ['parent_id' => $parentId] : $data;

        $model::create($data);

        return response()->json([
            'taxonomy_type'  => $taxonomyType,
            'all_taxonomies' => $this->getAllTaxonomies($model, $taxonomyType)
        ]);
    }

    public function update(Request $request)
    {
        list($taxonomyType, $model) = $this->getModel($request);

        $taxonomy = $model::whereId($request->id)->firstOrFail();

        $data = $request->validate([
            'id'          => 'required|exists:' . str_plural($taxonomyType) . ',id',
            'label'       => 'required',
            'description' => 'nullable',
            'parent_id'   => 'nullable|exists:' . str_plural($taxonomyType) . ',id',
        ]);

        $parentId = optional($data)['parent_id'];

        $data = [
            'label'       => $data['label'],
            'description' => $data['description'],
        ];

        $data = ($taxonomyType == 'category') ? $data + ['parent_id' => $parentId] : $data;

        $taxonomy->update($data);

        return response()->json([
            'taxonomy_type'  => $taxonomyType,
            'all_taxonomies' => $this->getAllTaxonomies($model, $taxonomyType)
        ]);


    }

    public function destroy(Request $request)
    {
        list($taxonomyType, $model) = $this->getModel($request);

        $counts = method_exists($model, 'children') ? ['posts', 'children'] : ['posts'];

        $taxonomy = $model::whereId($request->id)->withCount($counts)->firstOrFail();

        if ($taxonomy->posts_count > 0) {
            return response("Not able to delete taxonomy. It is associated with posts or might have direct children", 500);
        }

        if (method_exists($taxonomy, 'children') && $taxonomy->children->count()) {
            return response("Not able to delete taxonomy. It is associated with posts or might have direct children", 500);
        }

        $taxonomy->delete();

        return response()->json([
            'taxonomy_type'  => $taxonomyType,
            'all_taxonomies' => $this->getAllTaxonomies($model, $taxonomyType)
        ]);
    }

    protected function getAllTaxonomies($model, $type)
    {
        if ($type == 'category') {
            return $model::whereParentId(null)->with('children')->get();
        }

        return $model::get();
    }

    /**
     * @param Request $request
     * @return array
     */
    protected function getModel(Request $request): array
    {
        $taxonomyType = $request->taxonomy_type;

        $methodsName = "get" . title_case($taxonomyType) . "Model";

        $model = method_exists($this, $methodsName) ? $this->$methodsName() : abort(500, "Not able to find {$taxonomyType}");

        return array($taxonomyType, $model);
    }

    protected function getCategoryModel()
    {
        return Category::class;

    }

    protected function getTagModel()
    {
        return Tag::class;

    }

}