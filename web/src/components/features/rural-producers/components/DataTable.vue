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
      v-model:selection="selectedProducer"
      selectionMode="single"
      :metaKeySelection="metaKey"
      :loading="loading"
      :globalFilterFields="[
        'name',
        'document',
        'email',
        'telephone',
        'registration_date',
        'address',
      ]"
      :value="producers"
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
      <template #empty>Nenhum produtor encontrado</template>
      <template #loading>
        <div class="flex items-center flex-col gap-2">
          <i class="pi pi-spin pi-spinner" style="font-size: 2rem"></i>
          <span> Carregando os produtores rurais. Aguarde...</span>
        </div>
      </template>
      <Column field="name" header="Nome"> </Column>
      <Column field="document" header="Documento"></Column>
      <Column field="telephone" header="Telefone"></Column>
      <Column field="address" header="Endereço"></Column>
      <Column field="email" header="E-mail"></Column>
      <Column
        field="registration_date"
        header="Data de Registro"
        dataType="date"
      >
        <template #body="{ data }">
          {{ formatDate(data.registration_date) }}
        </template>
      </Column>
      <Column
        headerStyle="width: 5rem; text-align: center"
        bodyStyle="text-align: center; overflow: visible"
      >
        <template #body="{ data }">
          <ReportPdf :herdId="data.id" />
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
      :producerData="selectedProducer"
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
  deleteRuralProducer,
  getAllRuralProducers,
} from "../../../../services/rural-producers";
import CreateForm from "./CreateForm.vue";
import ReportPdf from "./ReportPdf.vue";
import UpdateForm from "./UpdateForm.vue";

const loading = ref(true);
const producers = ref([]);
const selectedProducer = ref();
const metaKey = ref(true);
const toast = useToast();
const displayModal = ref(false);
const confirm = useConfirm();

watch(selectedProducer, (newValue) => {
  if (newValue) {
    displayModal.value = true;
  }
});

const confirmDelete = (producer) => {
  confirm.require({
    message: `Deseja realmente deletar ${producer.name}?`,
    header: "Confirmação",
    icon: "pi pi-exclamation-triangle",
    accept: async () => {
      try {
        const { message } = await deleteRuralProducer(producer.id);
        toast.add({
          severity: "success",
          summary: message,
        });
        producers.value = producers.value.filter((p) => p.id !== producer.id);
      } catch (error) {
        toast.add({
          severity: "error",
          summary: "Falha ao deletar produtor",
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
  document: {
    value: null,
    matchMode: "starts_with",
  },
  email: {
    value: null,
    matchMode: "starts_with",
  },
  telephone: {
    value: null,
    matchMode: "starts_with",
  },
  address: {
    value: null,
    matchMode: "starts_with",
  },
  registration_date: {
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
    const { data } = await getAllRuralProducers();
    loading.value = false;
    producers.value = data;
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
