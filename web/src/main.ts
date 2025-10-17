import Aura from "@primeuix/themes/aura";
import { createPinia } from "pinia";
import "primeicons/primeicons.css";
import { ConfirmationService, ToastService } from "primevue";
import PrimeVue from "primevue/config";
import { createApp } from "vue";
import App from "./App.vue";
import { router } from "./routes";
import "./styles/main.css";

const app = createApp(App);
const pinia = createPinia();
app.use(PrimeVue, {
  theme: {
    preset: Aura,
  },
});
app.use(router);
app.use(pinia);
app.use(ToastService);
app.use(ConfirmationService);

app.mount("#app");
