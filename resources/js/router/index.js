import { createWebHistory, createRouter } from "vue-router";
import { useAuthStore } from "../stores/auth";

/* Guest Component */
const Login = () => import('@/pages/LoginPage.vue')
// const Register = () => import('@/components/Register.vue')

/* Layouts */
const AuthLayout = () => import('@/layouts/AuthLayout.vue')

/* Authenticated Component */
const Products = () => import('@/pages/ProductsPage.vue')
const AddProduct = () => import('@/pages/AddProductPage.vue')

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
                name: 'products',
                path: '/products',
                component: Products,
                meta: {
                    title: `Products`
                }
            },
            {
                name: 'add_product',
                path: '/add_product',
                component: AddProduct,
                meta: {
                    title: `Add Product`
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
