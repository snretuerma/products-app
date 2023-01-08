<template>
    <a-layout id="auth-container" has-sider>
        <a-layout-sider
            class="fill-height"
            :style="{
                overflow: 'auto',
                height: '100vh',
                position: 'fixed',
                left: 0,
                top: 0,
                bottom: 0,
            }"
        >
            <div class="logo"></div>
            <a-menu
                v-model:selectedKeys="selectedKeys"
                theme="dark"
                mode="inline"
            >
                <a-menu-item key="1">
                    <shopping-outlined />
                    <span>Products</span>
                </a-menu-item>
                <a-menu-item key="2" @click.prevent="logout">
                    <logout-outlined />
                    <span>Logout</span>
                </a-menu-item>
            </a-menu>
        </a-layout-sider>
        <a-layout class="fill-height" :style="{ marginLeft: '200px' }">
            <!-- <a-layout-header style="background: #fff; padding: 0" /> -->
            <a-layout-content
                :style="{
                    margin: '24px 16px 0',
                    padding: '24px',
                    background: '#fff',
                    overflow: 'initial',
                    minHeight: '100vh',
                }"
            >
                <router-view />
            </a-layout-content>
        </a-layout>
    </a-layout>
</template>

<script>
import { ref } from "vue";
import { useAuthStore } from "@/stores/auth";
import { useRouter } from "vue-router";
import { ShoppingOutlined, LogoutOutlined } from "@ant-design/icons-vue";

export default {
    components: {
        ShoppingOutlined,
        LogoutOutlined,
    },
    setup() {
        const authStore = useAuthStore();
        const router = useRouter();

        const logout = () => {
            axios.post("/logout").then(async (response) => {
                authStore.$patch({
                    authUser: {},
                    isLoggedIn: false,
                });
                localStorage.removeItem("auth");
                router.push({ name: "login" });
            });
        };
        return {
            logout,
            selectedKeys: ref(["1"]),
            collapsed: ref(false),
        };
    },
};
</script>

<style>
#auth-container {
    height: 100vh;
}

.fill-height {
    min-height: 100vh;
}

.header-trigger {
    font-size: 15px;
    line-height: 64px;
    padding: 0px 15px;
    cursor: pointer;
    transition: color 0.3s;
}

.header-trigger:hover {
    color: #1890ff;
}

.logo {
    height: 40px;
    background: rgba(255, 255, 255, 0.3);
    margin: 10px;
}

/* .site-layout .site-layout-background {
    background: #fff;
} */
</style>
