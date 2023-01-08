<template>
    <a-layout id="container">
        <a-layout-content>
            <a-space></a-space>
            <a-card id="login-card" title="Login" style="width: 500px">
                <a-form
                    :model="form"
                    :label-col="{ span: 7 }"
                    :wrapper-col="{ span: 16 }"
                    :labelWrap="true"
                    labelAlign="left"
                    @finish="onSubmit"
                    @finishFailed="onSubmitFailed"
                >
                    <a-form-item
                        label="Username/Email"
                        name="login_field"
                        :rules="[
                            {
                                required: true,
                                message: 'Please input your username or email!',
                            },
                        ]"
                    >
                        <a-input v-model:value="form.login_field">
                            <template #prefix>
                                <UserOutlined class="site-form-item-icon" />
                            </template>
                        </a-input>
                    </a-form-item>

                    <a-form-item
                        label="Password"
                        name="password"
                        :rules="[
                            {
                                required: true,
                                message: 'Please input your password!',
                            },
                        ]"
                    >
                        <a-input-password v-model:value="form.password">
                            <template #prefix>
                                <LockOutlined class="site-form-item-icon" />
                            </template>
                        </a-input-password>
                    </a-form-item>

                    <a-form-item name="remember" :wrapper-col="{ span: 16 }">
                        <a-checkbox v-model:checked="form.remember"
                            >Remember me</a-checkbox
                        >
                    </a-form-item>

                    <a-form-item :wrapper-col="{ span: 24 }">
                        <a-button
                            type="primary"
                            html-type="submit"
                            id="login-form-button"
                            >Submit</a-button
                        >
                    </a-form-item>
                </a-form>
            </a-card>
            <a-space></a-space>
        </a-layout-content>
    </a-layout>
</template>

<script>
import { reactive, computed } from "vue";
import { UserOutlined, LockOutlined } from "@ant-design/icons-vue";
import { useAuthStore } from "@/stores/auth";
import { useRouter } from "vue-router";

export default {
    components: {
        UserOutlined,
        LockOutlined,
    },
    setup() {
        const authStore = useAuthStore();
        const router = useRouter();
        const form = reactive({
            login_field: "",
            password: "",
            remember: true,
        });

        const onSubmit = (values) => {
            axios.get("/sanctum/csrf-cookie").then((response) => {
                axios.post("/login", values).then(async (response) => {
                    authStore.$patch({
                        authUser: response.data,
                        isLoggedIn: true,
                    });
                    localStorage.setItem(
                        "auth",
                        JSON.stringify(authStore.$state)
                    );
                    router.push({ name: "products" });
                });
            });
        };

        const onSubmitFailed = (errorInfo) => {
            console.log("Failed:", errorInfo);
        };

        const disabled = computed(() => {
            return !(form.login_field && form.password);
        });

        return {
            form,
            onSubmit,
            onSubmitFailed,
            disabled,
        };
    },
};
</script>

<style>
#container {
    position: relative;
    width: 100%;
    height: 100vh;
}
#login-card {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}
#login-form-button {
    width: 100%;
}
</style>
