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
      v-model:selection="selectedProperties"
      selectionMode="single"
      :metaKeySelection="metaKey"
      :loading="loading"
      :globalFilterFields="[
        'name',
        'municipality',
        'uf',
        'state_registration',
        'total_area',
        'producer_id',
      ]"
      :value="properties"
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
      <template #empty>Nenhuma propriedade encontrada</template>
      <template #loading>
        <div class="flex items-center flex-col gap-2">
          <i class="pi pi-spin pi-spinner" style="font-size: 2rem"></i>
          <span> Carregando as propriedades. Aguarde...</span>
        </div>
      </template>
      <Column field="name" header="Nome"> </Column>
      <Column field="municipality" header="Município"></Column>
      <Column field="uf" header="UF"></Column>
      <Column field="state_registration" header="Inscrição estadual"></Column>
      <Column field="total_area" header="Area total"></Column>
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
      :propertyData="selectedProperties"
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
import {
  deleteProperty,
  getAllProperties,
} from "../../../../services/properties";
import CreateForm from "./CreateForm.vue";
import UpdateForm from "./UpdateForm.vue";

const loading = ref(true);
const properties = ref([]);
const selectedProperties = ref();
const metaKey = ref(true);
const toast = useToast();
const displayModal = ref(false);
const confirm = useConfirm();

watch(selectedProperties, (newValue) => {
  if (newValue) {
    displayModal.value = true;
  }
});

const confirmDelete = (property) => {
  confirm.require({
    message: `Deseja realmente deletar ${property.name}?`,
    header: "Confirmação",
    icon: "pi pi-exclamation-triangle",
    accept: async () => {
      try {
        const { message } = await deleteProperty(property.id);
        toast.add({
          severity: "success",
          summary: message,
        });
        properties.value = properties.value.filter((p) => p.id !== property.id);
      } catch (error) {
        toast.add({
          severity: "error",
          summary: "Falha ao deletar a propriedade",
        });
      }
    },
  });
};

const handleCloseModal = () => (displayModal.value = false);

const filters = ref({
  global: { value: null, matchMode: "contains" },
  name: {
    value: null,
    matchMode: "contains",
  },
  municipality: {
    value: null,
    matchMode: "starts_with",
  },
  uf: {
    value: null,
    matchMode: "starts_with",
  },
  state_registration: {
    value: null,
    matchMode: "starts_with",
  },
  total_area: {
    value: null,
    matchMode: "starts_with",
  },
  producer_id: {
    value: null,
    matchMode: "date_is",
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
    const { data } = await getAllProperties();
    loading.value = false;
    properties.value = data;
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
