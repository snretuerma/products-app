<template>
    <div>
        Auth Layout
        <div>
            <a-button type="primary" @click.prevent="logout"
                >Primary Button</a-button
            >
        </div>
    </div>
</template>

<script>
import { useAuthStore } from "@/stores/auth";
import { useRouter } from "vue-router";

export default {
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

        return { logout };
    },
};
</script>
