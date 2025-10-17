<script setup lang="ts">
import { Button } from "primevue";
import { useToast } from "primevue/usetoast";
import { env } from "../../../../env";
import { useAuthStore } from "../../../../stores/auth";

const props = defineProps<{ herdId: number }>();

const authStore = useAuthStore();

const toast = useToast();

const generateReport = async () => {
  const response = await fetch(
    `${env.data?.VITE_API_URL}/reports/herds/${props.herdId}`,
    {
      headers: {
        Authorization: `Bearer ${authStore.token}`,
      },
    }
  );

  if (!response.ok) {
    throw new Error("Erro ao gerar relatório");
  }

  const blob = await response.blob();

  const url = globalThis.URL.createObjectURL(blob);

  const link = document.createElement("a");
  link.href = url;
  link.setAttribute("download", "relatorio.pdf");

  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);

  globalThis.URL.revokeObjectURL(url);

  toast.add({
    severity: "success",
    summary: "Sucesso",
    detail: "PDF baixado com sucesso!",
    life: 3000,
  });
};
</script>

<template>
  <Toast />
  <Button icon="pi pi-file-pdf" v-on:click="generateReport" />
</template>
