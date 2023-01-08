import { createWebHistory, createRouter } from "vue-router";
import { useAuthStore } from "../stores/auth";

/* Guest Component */
const Login = () => import('@/pages/LoginPage.vue')
// const Register = () => import('@/components/Register.vue')

/* Layouts */
const AuthLayout = () => import('@/layouts/AuthLayout.vue')

/* Authenticated Component */
const Dashboard = () => import('@/pages/DashboardPage.vue')

const routes = [
    {
        name: 'login',
        path: '/login',
        component: Login,
        meta: {
            middleware: "guest",
            title: `Login`
        }
    },
    {
        path: '/',
        component: AuthLayout,
        meta: {
            middleware: "auth"
        },
        children: [
            {
                name: 'dashboard',
                path: '/',
                component: Dashboard,
                meta: {
                    title: `Dashboard`
                }
            }
        ]
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    const authStore = useAuthStore();
    document.title = to.meta.title
    if (to.meta.middleware == "guest") {
        if (authStore.isLoggedIn) {
            next({ name: "dashboard" })
        }
        next()
    } else {
        if (authStore.isLoggedIn) {
            next()
        } else {
            next({ name: "login" })
        }
    }
});

export default router;
