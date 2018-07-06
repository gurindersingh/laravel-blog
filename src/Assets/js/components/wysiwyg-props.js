export const props = {
    name: {
        type: 'string' 
    },
    value: {
        type: ''
    },

    placeholder: {
        type: String,
        default: null
    },

    dataToc: {
        type: String
    },

    fileUploadUrl: {
        type: String
    },
    fileUploadName: {
        type: String,
        default: 'file'
    },
    fileDeleteUrl: {
        type: String
    },
    toolbarButtons: {
        type: Array,
        default: function () {
            return [];
        }
    },
    maxFileSize: {
        type: Number,
        default: 9
    },
    acceptedFileTypes: {
        type: String,
        default: '(\\.|\\/)(jpe?g|png)',
    },
    dataHeaders: {
        type: Object
    },
    editorHeight: {
        type: String,
        default: '450px'
    },
    enableCode: {
        type: Boolean,
        default: true
    },
    enableToc: {
        type: Boolean,
        default: true
    },
    scriptsToLoad : {
        types: String,
        required: true
    },
    insertLabels: {
        types: Object,
        default: function () {
            let plus = '<svg xmlns="http://www.w3.org/2000/svg" version="1.1"  width="18" height="18" viewBox="0 0 24 24">\n' +
                '<path fill="currentColor" d="M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z" /></svg>';

            let h1 = '<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" fill="none" version="1.1"  width="18" ' +
                'height="18" viewBox="0 0 24 24">\n' +
                '<path d="M3,4H5V10H9V4H11V18H9V12H5V18H3V4M14,18V16H16V6.31L13.5,7.75V5.44L16,4H18V16H20V18H14Z" /></svg>';

            let h2 = '<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" version="1.1"  width="18" height="18" ' +
                'viewBox="0 0 24 24"><path d="M3,4H5V10H9V4H11V18H9V12H5V18H3V4M21,18H15A2,2 0 0,1 13,16C13,15.47 13.2,15 13.54,' +
                '14.64L18.41,9.41C18.78,9.05 19,8.55 19,8A2,2 0 0,0 17,6A2,2 0 0,0 15,8H13A4,4 0 0,1 17,4A4,4 0 0,1 21,8C21,' +
                '9.1 20.55,10.1 19.83,10.83L15,16H21V18Z" /></svg>';

            let h3 = '<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" version="1.1"  width="18" height="18" viewBox="0 0 24 24">\n' +
                '<path d="M3,4H5V10H9V4H11V18H9V12H5V18H3V4M15,4H19A2,2 0 0,1 21,6V16A2,2 0 0,1 19,18H15A2,2 0 0,' +
                '1 13,16V15H15V16H19V12H15V10H19V6H15V7H13V6A2,2 0 0,1 15,4Z" /></svg>';

            let h4 = '<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" version="1.1"  width="18" height="18" viewBox="0 0 24 24">\n' +
                '<path d="M3,4H5V10H9V4H11V18H9V12H5V18H3V4M18,18V13H13V11L18,4H20V11H21V13H20V18H18M18,11V7.42L15.45,11H18Z" /></svg>';

            let h5 = '<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" version="1.1"  width="18" height="18" viewBox="0 0 24 24">\n' +
                '<path d="M3,4H5V10H9V4H11V18H9V12H5V18H3V4M15,4H20V6H15V10H17A4,4 0 0,1 21,14A4,4 0 0,1 17,' +
                '18H15A2,2 0 0,1 13,16V15H15V16H17A2,2 0 0,0 19,14A2,2 0 0,0 17,12H15A2,2 0 0,1 13,10V6A2,2 0 0,1 15,4Z" /></svg>';

            let h6 = '<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" version="1.1"  width="18" height="18" viewBox="0 0 24 24">\n' +
                '<path d="M3,4H5V10H9V4H11V18H9V12H5V18H3V4M15,4H19A2,2 0 0,1 21,6V7H19V6H15V10H19A2,2 0 0,1 21,' +
                '12V16A2,2 0 0,1 19,18H15A2,2 0 0,1 13,16V6A2,2 0 0,1 15,4M15,12V16H19V12H15Z" /></svg>';

            let image = '<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" version="1.1"  width="18" height="18" viewBox="0 0 24 24">\n' +
                '<path d="M5,3A2,2 0 0,0 3,5V19A2,2 0 0,0 5,21H14.09C14.03,20.67 14,20.34 14,20C14,19.32 14.12,18.64 14.35,18H5L8.5,' +
                '13.5L11,16.5L14.5,12L16.73,14.97C17.7,14.34 18.84,14 20,14C20.34,14 20.67,14.03 21,14.09V5C21,3.89 20.1,3 19,3H5M19,' +
                '16V19H16V21H19V24H21V21H24V19H21V16H19Z" /></svg>';

            let video = '<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" version="1.1"  width="18" height="18" viewBox="0 0 24 24">\n' +
                '<path d="M17,10.5V7A1,1 0 0,0 16,6H4A1,1 0 0,0 3,7V17A1,1 0 0,0 4,18H16A1,1 0 0,0 17,17V13.5L21,17.5V6.5L17,10.5Z"' +
                ' /></svg>';

            let code = '<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" version="1.1"  width="18" height="18" viewBox="0 0 24 24">\n' +
                '<path fill="currentColor" d="M14.6,16.6L19.2,12L14.6,7.4L16,6L22,12L16,18L14.6,16.6M9.4,16.6L4.8,12L9.4,7.4L8,6L2,12L8,' +
                '18L9.4,16.6Z" /></svg>';

            return {
                plus: plus,
                image: image,
                video: video,
                code: code,
                h1: h1,
                h2: h2,
                h3: h3,
                h4: h4,
                h5: h5,
                h6: h6,
            }
        }
    }
};

export const DefaultButtons = [
    'bold',
    'italic',
    'underline',
    'anchor',
    'quote',
    'strikethrough',
];

export const SupportedLanguages = {
    html: 'HTML',
    java: 'Java',
    py: 'Python',
    bsh: 'Bash',
    sh: 'Shell',
    sql: 'SQL',
    xml: 'XML',
    css: 'CSS',
    js: 'Javascript',
    coffee: 'CoffeeScript',
    go: 'GO',
    json: 'JSON',
    vb: 'Visual Basic',
    php: 'PHP',
    perl: 'Perl',
    rb: 'Ruby',
};

export const FileUploadOptions = (instance) => {
    return { // https://github.com/blueimp/jQuery-File-Upload/wiki/Options
        url: instance.fileUploadUrl, // (string) A relative path to an upload script
        paramName: instance.fileUploadName,
        singleFileUploads: true,
        headers: instance.dataHeaders,
        //acceptFileTypes: /(\.|\/)(jpe?g|png)$/i, // (regexp) Regexp of accepted file types
        acceptFileTypes: (new RegExp(instance.acceptedFileTypes, "i")), // (regexp) Regexp of accepted file types
        maxFileSize: instance.maxFileSize * 1024 * 1024, // 5MB,
        always: (e, data) => {
            if (data.jqXHR.status !== 200) {
                let $place = instance.core.$el.find('.medium-insert-images.medium-insert-active');
                $place.remove();
            }
        }
    }
};