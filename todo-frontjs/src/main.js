import './assets/main.css'
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import { createApp } from 'vue'
import App from './App.vue'
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";
const app = createApp(App);
const options = {
    position: "bottom-right", 
};

app.use(Toast, options);
createApp(App).mount('#app')
