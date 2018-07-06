<template>
    <div>
        <ul class="tab-links list-unstyled d-f tt-u mb-0">
            <li v-for="(tab, index) in tabList"
                v-bind="tab.dataAttrs"
                :key="index"
                :class="{'active': isActive(index), 'disabled': tab.disabled}"
                class="cur-p"
                @click="select(index)">
                {{ tab.title }}
            </li>
        </ul>
        <div class="tab-content bgc-white p-3 bd">
            <slot></slot>
        </div>
    </div>
</template>

<script>
    export default {
        name: "vue-tabs",

        data() {
            return {
                tabList: [],
                activeTabIndex: 0
            }
        },

        mounted() {
            this.select(0);
            this.activeTabIndex = this.getInitialActiveTab();

            this.$parent.$on('select-tab', index => this.select(index));
        },

        methods: {

            isActive(index) {
                return this.activeTabIndex === index;
            },

            select(index) {
                const tab = this.tabList[index];
                
                if (tab && !tab.isDisabled) {
                    this.activeTabIndex = index;
                }
            },

            getInitialActiveTab() {
                const index = this.tabList.findIndex(tab => tab.active);
                return index === -1 ? 0 : index;
            }

        }
    }
</script>