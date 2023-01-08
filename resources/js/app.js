import './bootstrap';
import { createApp } from 'vue';
import { createPinia } from 'pinia'
import Antd from 'ant-design-vue';
import router from '@/router'
import app from './App.vue';
import 'ant-design-vue/dist/antd.css';


createApp(app)
.use(createPinia())
.use(router)
.use(Antd)
.mount('#app');