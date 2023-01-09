import { defineStore } from "pinia";
// import { useStorage } from '@vueuse/core'

export const useUIStore = defineStore({
    id: 'uiState',
    state: () => ({
        selected_item: JSON.parse(localStorage.getItem('selected_item')),
        add_edit: localStorage.getItem('add_edit')
    }),
    getters: {
    },
    actions: {

    }
});