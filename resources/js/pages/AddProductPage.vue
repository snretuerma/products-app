<template>
    <div>
        <a-page-header
            :title="`${ui.add_edit} Product`"
            @back="backToProductsPage"
        />
        <a-form
            :model="form"
            name="form"
            :label-col="{ span: 8 }"
            :wrapper-col="{ span: 24 }"
            layout="vertical"
            @finish="onSubmit()"
            @finishFailed="onSubmitFailed"
        >
            <a-steps :current="current_step">
                <a-step
                    v-for="item in steps"
                    :key="item.title"
                    :title="item.title"
                />
            </a-steps>
            <div class="steps-content">
                <div v-show="current_step == 0">
                    <a-form-item
                        label="Name"
                        name="name"
                        :rules="[
                            {
                                required: true,
                                message: 'Product name is required',
                            },
                        ]"
                    >
                        <a-input v-model:value="form.name" />
                    </a-form-item>

                    <a-form-item
                        label="Category"
                        name="category"
                        :rules="[
                            {
                                required: true,
                                message: 'Product category is required',
                            },
                        ]"
                    >
                        <a-select
                            v-model:value="form.category"
                            :options="category_options"
                        />
                    </a-form-item>
                    <a-form-item label="Description" name="description">
                        <QuillEditor
                            theme="snow"
                            contentType="html"
                            v-model:content="form.description"
                        />
                    </a-form-item>
                </div>
                <div v-show="current_step == 1">
                    <div class="clearfix">
                        <a-form-item label="Images" name="images">
                            <input
                                type="file"
                                accept="image/*"
                                multiple
                                @change="onFileSelected"
                            />
                        </a-form-item>
                    </div>
                </div>
                <div v-show="current_step == 2">
                    <a-form-item
                        label="Date & Time"
                        name="date_time"
                        :rules="[
                            {
                                required: true,
                                message: 'Product date & time is required',
                            },
                        ]"
                    >
                        <a-space direction="vertical" :size="12">
                            <a-date-picker
                                :show-time="{ format: 'HH:mm' }"
                                valueFormat="YYYY-MM-DD HH:mm"
                                format="YYYY-MM-DD HH:mm"
                                placeholder="Select Time"
                                v-model:value="form.date_time"
                            />
                        </a-space>
                    </a-form-item>
                </div>
            </div>
            <div id="steps-action" class="steps-action">
                <a-form-item :wrapper-col="{ offset: 10, span: 24 }">
                    <a-button
                        v-if="current_step < steps.length - 1"
                        type="primary"
                        @click="next"
                        >Next</a-button
                    >
                    <a-button
                        v-if="current_step == steps.length - 1"
                        type="primary"
                        html-type="submit"
                        id="login-form-button"
                        >Submit
                    </a-button>

                    <a-button
                        v-if="current_step > 0"
                        style="margin-left: 8px"
                        @click="prev"
                        >Previous</a-button
                    >
                </a-form-item>
            </div>
        </a-form>
    </div>
</template>

<script>
import { ref, reactive, onMounted } from "vue";
import { useRouter } from "vue-router";
import { useUIStore } from "@/stores/ui";
import { message } from "ant-design-vue";
import { QuillEditor } from "@vueup/vue-quill";
import "@vueup/vue-quill/dist/vue-quill.snow.css";
import { PlusOutlined } from "@ant-design/icons-vue";

function getBase64(file) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = () => resolve(reader.result);
        reader.onerror = (error) => reject(error);
    });
}

export default {
    components: {
        QuillEditor,
        PlusOutlined,
    },
    setup() {
        const router = useRouter();
        const ui = useUIStore();
        const current_step = ref(0);
        const form = reactive({
            name: "",
            category: "",
            description: "",
            date_time: "",
        });

        let fileList = ref([]);

        const onFileSelected = (event) => {
            fileList.value = Array.from(event.target.files);
        };

        const backToProductsPage = () => {
            ui.selected_item = {};
            localStorage.removeItem("selected_item");
            router.push({ name: "products" });
        };

        const next = () => {
            current_step.value++;
        };

        const prev = () => {
            current_step.value--;
        };

        const onSubmitFailed = () => {
            message.error("Error submitting form");
        };

        const onSubmit = async () => {
            const config = {
                headers: { "Content-Type": "multipart/form-data" },
            };
            if (form.description != null) {
                let formData = new FormData();
                fileList.value.forEach((file) => {
                    formData.append(`images[]`, file, file.name);
                });
                for (var key in form) formData.append(key, form[key] || "");
                if (ui.add_edit == "Add") {
                    const response = await axios.post(
                        "/api/products",
                        formData,
                        config
                    );
                    manualFormReset();
                    displayMessage(response.data);
                } else {
                    const response = await axios.post(
                        `/api/products/${ui.selected_item.id}?_method=PUT`,
                        formData,
                        config
                    );
                    manualFormReset();
                    displayMessage(response.data);
                    ui.selected_item = {};
                }
                localStorage.removeItem("selected_item");
                router.push({ name: "products" });
            } else {
                message.error("Product description is required");
            }
        };

        const onEdit = async () => {
            const config = {
                headers: { "Content-Type": "multipart/form-data" },
            };
            if (form.description != null) {
                let formData = new FormData();
                fileList.value.forEach((file) => {
                    formData.append(`images[]`, file, file.name);
                });
                console.log(form);
                for (var key in form) formData.append(key, form[key] || "");
                const response = await axios.patch(
                    `/api/products/${ui.selected_item.id}`,
                    formData,
                    config
                );
                displayMessage(response.data);
                ui.selected_item = {};
                manualFormReset();
                localStorage.removeItem("selected_item");
                router.push({ name: "products" });
            } else {
                message.error("Product description is required");
            }
        };

        const displayMessage = (value) => {
            value.type == "success"
                ? message.success(value.message)
                : message.error(value.message);
        };

        const manualFormReset = () => {
            form.name = "";
            form.category = "";
            form.description = "";
            form.date_time = "";
            fileList.value = [];
        };

        const buildForm = () => {
            form.name = ui.selected_item.name;
            form.category = ui.selected_item.category;
            form.description = ui.selected_item.description;
            form.date_time = ui.selected_item.date_time;
        };

        onMounted(() => {
            if (ui.selected_item != null) {
                buildForm();
            }
        });

        return {
            current_step,
            steps: [
                {
                    title: "Product Details",
                },
                {
                    title: "Product Images",
                },
                {
                    title: "Date",
                },
            ],
            form,
            category_options: [
                { value: "Convenience goods", label: "Convenience goods" },
                { value: "Shopping goods", label: "Shopping goods" },
                { value: "Specialty goods", label: "Specialty goods" },
                { value: "Unsought goods", label: "Unsought goods" },
            ],
            next,
            prev,
            onSubmit,
            onSubmitFailed,
            backToProductsPage,
            ui,
            fileList,

            onFileSelected,
        };
    },
};
</script>

<style scoped>
#steps-action {
    margin-top: 10px;
}
.ant-upload-select-picture-card i {
    font-size: 32px;
    color: #999;
}

.ant-upload-select-picture-card .ant-upload-text {
    margin-top: 8px;
    color: #666;
}
</style>
