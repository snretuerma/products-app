<template>
    <div>
        <a-list
            :loading="loading"
            :grid="{
                column: 3,
                gutter: 10,
                xs: 1,
                sm: 1,
                md: 3,
                lg: 3,
                xl: 6,
            }"
            :data-source="product_list.data"
        >
            <template #header>
                <a-page-header :ghost="false" title="Product List">
                    <template #extra>
                        <a-button key="1" type="primary"
                            >Create Product</a-button
                        >
                    </template>
                </a-page-header>
            </template>
            <template #renderItem="{ item }">
                <a-list-item>
                    <a-card hoverable style="width: 450px">
                        <template #cover>
                            <img
                                alt="example"
                                src="https://gw.alipayobjects.com/zos/rmsportal/JiqGstEfoWAOHiTxclqi.png"
                            />
                        </template>
                        <template #actions>
                            <setting-outlined
                                key="setting"
                                @click.prevent="
                                    console.log('show confirmation dialog')
                                "
                            />
                            <edit-outlined key="edit" />
                            <delete-outlined key="ellipsis" />
                        </template>
                        <a-card-meta
                            :title="item.name"
                            :description="item.category"
                        >
                            <!-- <template #avatar>
                                        <a-avatar
                                            src="https://joeschmoe.io/api/v1/random"
                                        />
                                    </template> -->
                        </a-card-meta>
                    </a-card>
                </a-list-item>
            </template>
            <template #footer>
                <!-- <a-pagination
                    v-model:current="product_list.current_page"
                    :total="product_list.total"
                    show-less-items
                    showTotal
                /> -->
            </template>
        </a-list>
    </div>
</template>

<script>
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import {
    EditOutlined,
    DeleteOutlined,
    SettingOutlined,
} from "@ant-design/icons-vue";
export default {
    components: {
        EditOutlined,
        DeleteOutlined,
        SettingOutlined,
    },
    setup() {
        const router = useRouter();
        let product_list = ref([]);
        let loading = ref(true);

        const fetchList = async () => {
            const response = await axios.get("/api/products");
            product_list.value = await response.data;
            loading.value = false;
        };

        onMounted(() => {
            fetchList();
        });

        return {
            product_list,
            loading,
            fetchList,
        };
    },
};
</script>
