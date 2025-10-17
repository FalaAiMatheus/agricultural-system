<script setup lang="ts">
import { Button } from "primevue";
import { useToast } from "primevue/usetoast";
import { env } from "../../../../env";
import { useAuthStore } from "../../../../stores/auth";

const authStore = useAuthStore();

const toast = useToast();

const generateReport = async () => {
  const response = await fetch(`${env.data?.VITE_API_URL}/reports/properties`, {
    headers: {
      Authorization: `Bearer ${authStore.token}`,
    },
  });

  const blob = await response.blob();

  const url = globalThis.URL.createObjectURL(blob);

  const link = document.createElement("a");
  link.href = url;
  link.setAttribute("download", "relatorio.xlsx");

  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);

  globalThis.URL.revokeObjectURL(url);

  toast.add({
    severity: "success",
    summary: "Sucesso",
    detail: "Relatório baixado com sucesso!",
    life: 3000,
  });
};
</script>

<template>
  <Toast />
  <Button
    label="Gerar relatório de todas as propriedades"
    icon="pi pi-file-excel"
    v-on:click="generateReport"
  />
</template>
