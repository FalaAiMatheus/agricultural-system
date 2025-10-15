import Aura from "@primeuix/themes/aura";
import { ToastService } from "primevue";
import PrimeVue from "primevue/config";
import { createApp } from "vue";
import App from "./App.vue";
import { router } from "./routes";
import "./styles/main.css";

const app = createApp(App);
app.use(PrimeVue, {
  theme: {
    preset: Aura,
  },
});
app.use(router);
app.use(ToastService);

app.mount("#app");
