import { defineStore } from "pinia";
// import { useStorage } from '@vueuse/core'

export const useAuthStore = defineStore({
    id: 'authState',
    state: () => ({
        authUser: JSON.parse(localStorage.getItem('auth'))?.authUser,
        isLoggedIn: JSON.parse(localStorage.getItem('auth'))?.isLoggedIn,
    }),
    getters: {
        authName: (state) => state.authUser?.name,
    },
    actions: {

    }
});