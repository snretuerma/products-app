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
                        <a-button
                            key="1"
                            type="primary"
                            @click.prevent="goToCreateProductsPage"
                            >Add Product</a-button
                        >
                    </template>
                    <div>
                        <a-row :gutter="16">
                            <a-col :span="12">
                                <a-form-item label="Search" name="search">
                                    <a-input
                                        v-model:value="search_text"
                                        placeholder="Search Name/Description"
                                    />
                                </a-form-item>
                            </a-col>
                            <a-col :span="10">
                                <a-form-item label="Category" name="category">
                                    <a-select
                                        v-model:value="search_category"
                                        mode="tags"
                                        style="width: 100%"
                                        placeholder="Categories"
                                        :options="category_options"
                                    ></a-select>
                                </a-form-item>
                            </a-col>
                            <a-col :span="2">
                                <a-button
                                    type="primary"
                                    @click.prevent="onSearch"
                                    >Search</a-button
                                >
                            </a-col>
                        </a-row>
                    </div>
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
                            <setting-outlined key="setting" />
                            <edit-outlined key="edit" />
                            <delete-outlined
                                key="delete"
                                @click.prevent="showDeleteModal(item)"
                            />
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
                <div id="pagination-container">
                    <a-row align="middle" justify="center">
                        <a-col :span="24" type="flex" align="middle">
                            <Bootstrap5Pagination
                                :align="center"
                                :data="product_list"
                                @pagination-change-page="fetchList"
                            />
                        </a-col>
                    </a-row>
                </div>

                <!-- <a-pagination
                    v-model:current="product_list.current_page"
                    :total="product_list.total"
                    show-less-items
                    showTotal
                /> -->
            </template>
        </a-list>
        <a-modal
            v-model:visible="delete_modal_visible"
            title="Delete Item"
            ok-text="Confirm"
            cancel-text="Cancel"
            @ok="confirmDeleteModal"
            @cancel="hideDeleteModal"
        >
            <p>Are you sure you want to delete this item :</p>
            <p>
                <a-space direction="vertical">
                    Name: <strong>{{ selected_item?.name }}</strong>
                    Category:

                    <a-tag color="pink">{{ selected_item?.category }}</a-tag>
                </a-space>
            </p>
        </a-modal>
    </div>
</template>

<script>
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import { message } from "ant-design-vue";
import {
    EditOutlined,
    DeleteOutlined,
    SettingOutlined,
} from "@ant-design/icons-vue";
import { Bootstrap5Pagination } from "laravel-vue-pagination";

export default {
    components: {
        EditOutlined,
        DeleteOutlined,
        SettingOutlined,
        Bootstrap5Pagination,
    },
    setup() {
        const router = useRouter();
        let delete_modal_visible = ref(false);
        let selected_item = ref({});
        let search_category = ref([]);
        let search_text = ref("");
        let product_list = ref([]);
        let loading = ref(true);

        const fetchList = async (page = 1) => {
            const response = await axios.get(
                `/api/products?page=${page}&name=${
                    search_text.value
                }&description=${search_text.value}&category=${
                    Array.isArray(search_category.value) ||
                    search_category.value.length > 0
                        ? JSON.stringify(search_category.value)
                        : ""
                }`
            );
            product_list.value = await response.data;
            loading.value = false;
        };

        const onSearch = async () => {
            fetchList();
        };

        const showDeleteModal = (item) => {
            delete_modal_visible.value = true;
            selected_item.value = item;
        };

        const hideDeleteModal = () => {
            delete_modal_visible.value = false;
        };

        const confirmDeleteModal = async () => {
            const response = await axios.delete(
                `/api/products/${selected_item.value.id}`
            );
            await fetchList();
            hideDeleteModal();
            displayMessage(response.data);
        };

        const displayMessage = (value) => {
            value.type == "success"
                ? message.success(value.message)
                : message.error(value.message);
        };

        const goToCreateProductsPage = () => {
            router.push({ name: "add_product" });
        };

        onMounted(() => {
            fetchList();
        });

        return {
            search_text,
            search_category,
            product_list,
            loading,
            selected_item,
            delete_modal_visible,
            showDeleteModal,
            hideDeleteModal,
            confirmDeleteModal,
            goToCreateProductsPage,
            onSearch,

            fetchList,

            category_options: [
                { value: "Convenience goods", label: "Convenience goods" },
                { value: "Shopping goods", label: "Shopping goods" },
                { value: "Specialty goods", label: "Specialty goods" },
                { value: "Unsought goods", label: "Unsought goods" },
            ],
        };
    },
};
</script>

<style>
#pagination-container {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-left: 200px;
}
.pagination {
    text-align: center;
    min-width: 696px;
    list-style: none;
    padding-top: 20px;
    text-align: center;
}
.page-item a {
    display: inline;
    color: black !important;
    float: left;
    padding: 8px 16px;
    text-decoration: none;
}
.pagination .active a {
    background-color: #1890ff;
    color: white !important;
}
</style>
