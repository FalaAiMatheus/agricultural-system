<template>
  <Dialog
    v-model:visible="props.visible"
    modal
    :header="`Editar rebanho ${props.herdsData?.species}`"
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
        <InputText name="species" type="text" placeholder="Nome" fluid />
        <Message
          v-if="$form.species?.invalid"
          severity="error"
          size="small"
          variant="simple"
          >{{ $form.species?.error.message }}</Message
        >
      </div>
      <div class="flex flex-col gap-1">
        <InputNumber name="quantity" placeholder="Quantidade" fluid />
        <Message
          v-if="$form.quantity?.invalid"
          severity="error"
          size="small"
          variant="simple"
          >{{ $form.quantity?.error.message }}</Message
        >
      </div>
      <div class="flex flex-col gap-1">
        <Textarea name="purpose" rows="5" cols="30" style="resize: none" />
        <Message
          v-if="$form.purpose?.invalid"
          severity="error"
          size="small"
          variant="simple"
          >{{ $form.purpose.error?.message }}</Message
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
  InputNumber,
  InputText,
  Message,
  Textarea,
  useToast,
} from "primevue";
import { defineProps, onMounted, ref, watch } from "vue";
import { z } from "zod";
import { getModifiedFields } from "../../../../core/utils";
import { updateHerd } from "../../../../services/herds";
import { getAllProperties } from "../../../../services/properties";

const props = defineProps({
  visible: {
    type: Boolean,
    required: true,
  },
  handleCloseModal: {
    type: Boolean,
    required: true,
  },
  herdsData: {
    type: Object,
    default: null,
  },
});
const properties = ref();

const initialValues = ref({
  species: "",
  quantity: 0,
  purpose: "",
});
const originalValues = ref({});

watch(
  () => props.herdsData,
  (newData) => {
    if (newData) {
      initialValues.value = {
        species: newData.species ?? "",
        quantity: newData.quantity ?? 0,
        purpose: newData.purpose ?? "",
      };
      originalValues.value = { ...initialValues.value };
    }
  },
  { immediate: true }
);
const toast = useToast();

const resolver = zodResolver(
  z.object({
    species: z.string().optional(),
    quantity: z.number().optional(),
    purpose: z.string().optional(),
  })
);

onMounted(() => {
  getAllProperties().then(({ data }) => (properties.value = data));
});

const onFormSubmit = async (e) => {
  if (!e.valid) return;

  const modifiedValues = getModifiedFields(e.values, props.herdsData);

  if (Object.keys(modifiedValues).length === 0) {
    toast.add({
      severity: "info",
      life: 3000,
      summary: "Nenhuma alteração detectada",
    });
    return;
  }

  try {
    const res = await updateHerd(modifiedValues, props.herdsData.id);
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
