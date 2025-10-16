<template>
  <Dialog
    v-model:visible="props.visible"
    modal
    :header="`Editar propriedade ${props.propertyData?.name}`"
    :style="{ width: '25rem' }"
  >
    <Form
      v-slot="$form"
      :initialValues
      :resolver
      @submit="onFormSubmit"
      class="flex flex-col gap-4 w-full"
    >
      <div class="flex flex-col gap-1">
        <InputText
          v-model="initialValues.name"
          name="name"
          type="text"
          placeholder="Nome"
          fluid
        />
        <Message
          v-if="$form.name?.invalid"
          severity="error"
          size="small"
          variant="simple"
          >{{ $form.name?.error.message }}</Message
        >
      </div>
      <div class="flex flex-col gap-1">
        <InputText name="municipality" type="text" placeholder="Email" fluid />
        <Message
          v-if="$form.municipality?.invalid"
          severity="error"
          size="small"
          variant="simple"
          >{{ $form.municipality?.error.message }}</Message
        >
      </div>
      <div class="flex flex-col gap-1">
        <InputMask
          name="state_registration"
          mask="999999999"
          placeholder="999999999"
          fluid
        />
        <Message
          v-if="$form.state_registration?.invalid"
          severity="error"
          size="small"
          variant="simple"
          >{{ $form.state_registration?.error.message }}</Message
        >
      </div>
      <div class="flex flex-col gap-1">
        <InputText name="uf" type="text" placeholder="UF" fluid />
        <Message
          v-if="$form.uf?.invalid"
          severity="error"
          size="small"
          variant="simple"
          >{{ $form.uf?.error.message }}</Message
        >
      </div>
      <div class="flex flex-col gap-1">
        <Select
          name="producer"
          :options="ruralProducers"
          optionLabel="name"
          optionValue="id"
          placeholder="Selecione o Produtor rural"
          fluid
        />
        <Message
          v-if="$form.producer?.invalid"
          severity="error"
          size="small"
          variant="simple"
          >{{ $form.producer?.error?.message }}</Message
        >
      </div>
      <div class="flex flex-col gap-1">
        <InputText
          name="total_area"
          type="text"
          placeholder="total_area"
          fluid
        />
        <Message
          v-if="$form.total_area?.invalid"
          severity="error"
          size="small"
          variant="simple"
          >{{ $form.total_area?.error.message }}</Message
        >
      </div>
      <div class="flex justify-end gap-2">
        <Button
          type="button"
          label="Cancelar"
          severity="secondary"
          @click="props.handleCloseModal"
        ></Button>
        <Button type="submit" label="Criar"></Button>
      </div>
    </Form>
  </Dialog>
</template>

<script setup>
import { Form } from "@primevue/forms";
import { zodResolver } from "@primevue/forms/resolvers/zod";
import {
  Button,
  Dialog,
  InputMask,
  InputText,
  Message,
  Select,
  useToast,
} from "primevue";
import { defineProps, onMounted, ref, watch } from "vue";
import { z } from "zod";
import { getModifiedFields } from "../../../../core/utils";
import { updateProperty } from "../../../../services/properties";
import { getAllRuralProducers } from "../../../../services/rural-producers";

const props = defineProps({
  visible: {
    type: Boolean,
    required: true,
  },
  handleCloseModal: {
    type: Boolean,
    required: true,
  },
  propertyData: {
    type: Object,
    default: null,
  },
});
const ruralProducers = ref();

const initialValues = ref({
  name: "",
  municipality: "",
  uf: "",
  state_registration: "",
  total_area: "",
  producer: { id: "" },
});
const originalValues = ref({});

watch(
  () => props.propertyData,
  (newData) => {
    if (newData) {
      initialValues.value = {
        name: newData.name ?? "",
        municipality: newData.municipality ?? "",
        uf: newData.uf ?? "",
        state_registration: newData.state_registration ?? "",
        total_area: newData.total_area ?? "",
        producer: { id: newData.producer?.id ?? "" },
      };
      originalValues.value = { ...initialValues.value };
    }
  },
  { immediate: true }
);
const toast = useToast();

const resolver = zodResolver(
  z.object({
    name: z.string().optional(),
    municipality: z.string().optional(),
    uf: z.string().optional(),
    state_registration: z.string().optional(),
    total_area: z.string().optional(),
    producer: z.object({
      id: z.number().min(1, "Produtor rural é obrigatório"),
    }),
  })
);

onMounted(() => {
  getAllRuralProducers().then(({ data }) => (ruralProducers.value = data));
});

const onFormSubmit = async (e) => {
  if (!e.valid) return;

  const modifiedValues = getModifiedFields(e.values, props.propertyData);

  if (Object.keys(modifiedValues).length === 0) {
    toast.add({
      severity: "info",
      life: 3000,
      summary: "Nenhuma alteração detectada",
    });
    return;
  }

  try {
    const res = await updateProperty(modifiedValues, props.propertyData.id);
    toast.add({
      severity: "success",
      life: 3000,
      summary: res.message,
    });
    props.handleCloseModal();
  } catch (error) {
    if (error instanceof Error) {
      toast.add({
        severity: "error",
        life: 3000,
        summary: error.message,
      });
    }
  }
};
</script>
