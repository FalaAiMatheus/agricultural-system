<template>
  <div class="p-4 md:p-8 min-h-screen">
    <h1 class="text-3xl font-bold mb-6">Relatórios Gerenciais</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
      <Card
        title="Propriedades por Município"
        icon="pi pi-building"
        value="150"
      />
      <Card title="Animais por Espécie" icon="pi pi-paw" value="25.000" />
      <Card
        title="Hectares por Cultura"
        icon="pi pi-chart-bar"
        value="5.000 ha"
      />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <Panel header="Total de Propriedades por Município">
        <Chart
          type="bar"
          :data="propriedadesData"
          :options="chartOptions"
          class="h-80"
        />
      </Panel>

      <Panel header="Hectares por Cultura">
        <Chart
          type="doughnut"
          :data="cultureData"
          :options="chartOptions"
          class="h-80"
        />
      </Panel>

      <Panel header="Animais por Espécie" class="lg:col-span-2">
        <DataTable :value="animalsData" tableStyle="min-width: 50rem">
          <Column field="especie" header="Espécie"></Column>
          <Column field="total" header="Total de Animais"></Column>
          <Column field="porcentagem" header="Porcentagem"> </Column>
        </DataTable>
      </Panel>
    </div>
  </div>
</template>

<script setup lang="ts">
import { Card, Column, DataTable, Panel } from "primevue";
import Chart from "primevue/chart";
import { computed, onMounted, ref } from "vue";
import { getAllHerds, type Herd } from "../../../services/herds";
import {
  getAllProductionUnits,
  type ProductionUnit,
} from "../../../services/production-units";

const properties = ref<ProductionUnit[]>([]);
const animals = ref<Herd[]>([]);

onMounted(() => {
  getAllProductionUnits().then(({ data }) => (properties.value = data));
  getAllHerds().then(({ data }) => (animals.value = data));
});

const propriedadesData = computed(() => {
  if (properties.value.length === 0) {
    return { labels: [], datasets: [] };
  }

  const uniquePropertiesByMunicipality = properties.value.reduce(
    (acc, unit) => {
      const muni = unit.properties?.municipality;
      const propertyId = unit.property_id;

      if (muni) {
        if (!acc[muni]) {
          acc[muni] = new Set();
        }
        acc[muni].add(propertyId);
      }
      return acc;
    },
    {} as Record<string, Set<number>>
  );

  const counts = Object.entries(uniquePropertiesByMunicipality).reduce(
    (acc, [muni, set]) => {
      acc[muni] = set.size;
      return acc;
    },
    {} as Record<string, number>
  );

  const labels = Object.keys(counts);
  const data = Object.values(counts);

  return {
    labels: labels,
    datasets: [
      {
        label: "Total de Propriedades",
        backgroundColor: "#4ade80",
        data: data,
      },
    ],
  };
});

const cultureData = computed(() => {
  if (properties.value.length === 0) {
    return { labels: [], datasets: [] };
  }

  const cultureTotals = properties.value.reduce((acc, unit) => {
    const name = unit.culture_name;
    const hectares = Number.parseFloat(unit.total_area_ha);

    if (!Number.isNaN(hectares)) {
      acc[name] = (acc[name] || 0) + hectares;
    }
    return acc;
  }, {} as Record<string, number>);

  const labels = Object.keys(cultureTotals);
  const data = Object.values(cultureTotals);

  const backgroundColors = [
    "#fde047",
    "#34d399",
    "#fca5a5",
    "#93c5fd",
    "#a78bfa",
    "#fb923c",
  ];

  return {
    labels: labels,
    datasets: [
      {
        data: data,
        backgroundColor: labels.map(
          (_, index) => backgroundColors[index % backgroundColors.length]
        ),
      },
    ],
  };
});

const animalsData = computed(() => {
  if (animals.value.length === 0) {
    return [];
  }

  const speciesTotals = animals.value.reduce((acc, unit) => {
    const name = unit.species;
    const quantity = unit.quantity;

    acc[name] = (acc[name] || 0) + quantity;
    return acc;
  }, {} as Record<string, number>);

  const geral = Object.values(speciesTotals).reduce(
    (sum, current) => sum + current,
    0
  );

  const formattedData = Object.entries(speciesTotals).map(
    ([especie, total]) => {
      const porcentagem = geral > 0 ? (total / geral) * 100 : 0;

      return {
        especie: especie,
        total: total,
        porcentagem: `${porcentagem.toFixed(1)}%`,
        porcentagem_valor: porcentagem,
      };
    }
  );

  return formattedData.sort((a, b) => b.total - a.total);
});

const chartOptions = ref({
  responsive: true,
  plugins: {
    legend: {
      position: "top",
    },
    title: {
      display: false,
    },
  },
});
</script>
