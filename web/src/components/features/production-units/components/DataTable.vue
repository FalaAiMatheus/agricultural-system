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
      v-model:selection="selectedProductionUnits"
      selectionMode="single"
      :metaKeySelection="metaKey"
      :loading="loading"
      :globalFilterFields="[
        'culture_name',
        'total_area_ha',
        'latitude',
        'longitude',
      ]"
      :value="productionUnits"
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
      <template #empty>Nenhuma unidade de produção encontrada</template>
      <template #loading>
        <div class="flex items-center flex-col gap-2">
          <i class="pi pi-spin pi-spinner" style="font-size: 2rem"></i>
          <span> Carregando as unidades de produção. Aguarde...</span>
        </div>
      </template>
      <Column field="culture_name" header="Cultura"> </Column>
      <Column field="total_area_ha" header="Area total Ha"></Column>
      <Column field="latitude" header="Latitude"></Column>
      <Column field="longitude" header="Longitude"></Column>
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
      :productionUnitData="selectedProductionUnits"
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
  deleteProductionUnit,
  getAllProductionUnits,
} from "../../../../services/production-units";
import CreateForm from "./CreateForm.vue";
import UpdateForm from "./UpdateForm.vue";

const loading = ref(true);
const productionUnits = ref([]);
const selectedProductionUnits = ref();
const metaKey = ref(true);
const toast = useToast();
const displayModal = ref(false);
const confirm = useConfirm();

watch(selectedProductionUnits, (newValue) => {
  if (newValue) {
    displayModal.value = true;
  }
});

const confirmDelete = (productionUnit) => {
  confirm.require({
    message: `Deseja realmente deletar ${productionUnit.culture_name}?`,
    header: "Confirmação",
    icon: "pi pi-exclamation-triangle",
    accept: async () => {
      try {
        const { message } = await deleteProductionUnit(productionUnit.id);
        toast.add({
          severity: "success",
          summary: message,
        });
        productionUnits.value = productionUnits.value.filter(
          (p) => p.id !== productionUnit.id
        );
      } catch (error) {
        toast.add({
          severity: "error",
          summary: "Falha ao deletar a unidade de produção",
        });
      }
    },
  });
};

const handleCloseModal = () => (displayModal.value = false);

const filters = ref({
  global: { value: null, matchMode: "contains" },
  culture_name: {
    value: null,
    matchMode: "contains",
  },
  total_area_ha: {
    value: null,
    matchMode: "starts_with",
  },
  longitude: {
    value: null,
    matchMode: "starts_with",
  },
  latitude: {
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
    const { data } = await getAllProductionUnits();
    loading.value = false;
    productionUnits.value = data;
  } catch (error) {
    toast.add({
      severity: "error",
      summary: "Falha ao buscar as unidades de produção",
    });
  } finally {
    loading.value = false;
  }
});
</script>
