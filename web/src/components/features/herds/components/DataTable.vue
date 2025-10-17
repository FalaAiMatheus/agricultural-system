<template>
  <div class="pt-4">
    <Toast />
    <ConfirmDialog />
    <div class="flex justify-center items-center mb-6 gap-2">
      <ToggleSwitch v-model="metaKey" inputId="input-metakey" />
      <label for="input-metakey">MetaKey</label>
    </div>
    <DataTable
      v-model:filters="filters"
      dataKey="id"
      filterDisplay="row"
      v-model:selection="selectedHerds"
      selectionMode="single"
      :metaKeySelection="metaKey"
      :loading="loading"
      :globalFilterFields="['species', 'quantity', 'purpose', 'update_date']"
      :value="herds"
      paginator
      :rows="5"
      showGridlines
      :rowsPerPageOptions="[5, 10, 20, 50]"
      tableStyle="min-width: 50rem"
    >
      <template #header>
        <div class="flex justify-between">
          <IconField>
            <InputIcon>
              <i class="pi pi-search" />
            </InputIcon>
            <InputText v-model="filters['global'].value" placeholder="Buscar" />
          </IconField>
          <CreateForm />
        </div>
      </template>
      <template #empty>Nenhuma rebanho encontrada</template>
      <template #loading>
        <div class="flex items-center flex-col gap-2">
          <i class="pi pi-spin pi-spinner" style="font-size: 2rem"></i>
          <span> Carregando os rebanhos. Aguarde...</span>
        </div>
      </template>
      <Column field="species" header="Nome"> </Column>
      <Column field="quantity" header="Quantidade"></Column>
      <Column field="purpose" header="Finalidade"></Column>
      <Column field="update_date" header="Data de atualização" dataType="date">
        <template #body="{ data }">
          {{ formatDate(data.update_date) }}
        </template>
      </Column>
      <Column
        headerStyle="width: 5rem; text-align: center"
        bodyStyle="text-align: center; overflow: visible"
      >
        <template #body="{ data }">
          <Button
            @click="confirmDelete(data)"
            severity="danger"
            icon="pi pi-trash"
            variant="outlined"
          />
        </template>
      </Column>
    </DataTable>
    <UpdateForm
      :visible="displayModal"
      :handleCloseModal="handleCloseModal"
      :herdsData="selectedHerds"
    />
  </div>
</template>

<script setup>
import {
  Button,
  Column,
  ConfirmDialog,
  DataTable,
  IconField,
  InputIcon,
  InputText,
  Toast,
  ToggleSwitch,
  useConfirm,
  useToast,
} from "primevue";
import { onMounted, ref, watch } from "vue";
import { deleteHerd, getAllHerds } from "../../../../services/herds";
import CreateForm from "./CreateForm.vue";
import UpdateForm from "./UpdateForm.vue";

const loading = ref(true);
const herds = ref([]);
const selectedHerds = ref();
const metaKey = ref(true);
const toast = useToast();
const displayModal = ref(false);
const confirm = useConfirm();

watch(selectedHerds, (newValue) => {
  if (newValue) {
    displayModal.value = true;
  }
});

const confirmDelete = (herd) => {
  confirm.require({
    message: `Deseja realmente deletar ${herd.species}?`,
    header: "Confirmação",
    icon: "pi pi-exclamation-triangle",
    accept: async () => {
      try {
        const { message } = await deleteHerd(herd.id);
        toast.add({
          severity: "success",
          summary: message,
        });
        herds.value = herds.value.filter((p) => p.id !== herd.id);
      } catch (error) {
        toast.add({
          severity: "error",
          summary: "Falha ao deletar o rebanho",
        });
      }
    },
  });
};

const handleCloseModal = () => (displayModal.value = false);

const filters = ref({
  global: { value: null, matchMode: "contains" },
  species: {
    value: null,
    matchMode: "contains",
  },
  quantity: {
    value: null,
    matchMode: "starts_with",
  },
  purpose: {
    value: null,
    matchMode: "starts_with",
  },
  update_date: {
    value: null,
    matchMode: "starts_with",
  },
});

const formatDate = (value) => {
  if (!value) return "";
  return new Date(value).toLocaleDateString("pt-BR", {
    timeZone: "UTC",
    dateStyle: "full",
  });
};

onMounted(async () => {
  try {
    const { data } = await getAllHerds();
    loading.value = false;
    herds.value = data;
  } catch (error) {
    toast.add({
      severity: "error",
      summary: "Falha ao buscar produtores rurais",
    });
  } finally {
    loading.value = false;
  }
});
</script>
