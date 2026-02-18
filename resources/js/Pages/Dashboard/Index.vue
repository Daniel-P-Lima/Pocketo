<script setup>
import { ref, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import MonthPicker from '../../Components/MonthPicker.vue';
import TransactionCard from '../../Components/TransactionCard.vue';
import { Chart, LineController, DoughnutController, LineElement, ArcElement, PointElement,
         CategoryScale, LinearScale, Tooltip, Legend, Filler } from 'chart.js';

Chart.register(LineController, DoughnutController, LineElement, ArcElement, PointElement,
               CategoryScale, LinearScale, Tooltip, Legend, Filler);
Chart.defaults.font.family = "'Inter', sans-serif";
Chart.defaults.font.size = 11;

const props = defineProps({
    month: Number,
    year: Number,
    totalIncome: Number,
    totalExpense: Number,
    balance: Number,
    recentTransactions: Array,
    budgetAlerts: Array,
    trendData: Object,
    categoryData: Array,
});

function formatBrl(amount) {
    if (!amount && amount !== 0) return '';
    const num = parseFloat(amount);
    if (isNaN(num)) return '';
    return num.toLocaleString('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });
}

const trendCanvas = ref(null);
const categoryCanvas = ref(null);

onMounted(() => {
    if (trendCanvas.value && props.trendData) {
        new Chart(trendCanvas.value, {
            type: 'line',
            data: {
                labels: props.trendData.labels,
                datasets: [
                    {
                        label: 'Receitas',
                        data: props.trendData.income.map(v => v),
                        borderColor: '#10b981',
                        backgroundColor: '#10b98120',
                        fill: true,
                        tension: 0.3,
                        pointRadius: 3,
                        pointBackgroundColor: '#10b981',
                    },
                    {
                        label: 'Despesas',
                        data: props.trendData.expense.map(v => v),
                        borderColor: '#ef4444',
                        backgroundColor: '#ef444420',
                        fill: true,
                        tension: 0.3,
                        pointRadius: 3,
                        pointBackgroundColor: '#ef4444',
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 12, padding: 12 } },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => `${ctx.dataset.label}: R$ ${formatBrl(ctx.parsed.y)}`,
                        },
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: (v) => `R$ ${formatBrl(v)}`,
                            maxTicksLimit: 5,
                        },
                        grid: { color: '#f3f4f6' },
                    },
                    x: { grid: { display: false } },
                },
            },
        });
    }

    if (categoryCanvas.value && props.categoryData?.length) {
        new Chart(categoryCanvas.value, {
            type: 'doughnut',
            data: {
                labels: props.categoryData.map(c => c.name),
                datasets: [{
                    data: props.categoryData.map(c => c.total),
                    backgroundColor: props.categoryData.map(c => c.color),
                    borderWidth: 0,
                    hoverOffset: 4,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 10, padding: 8, font: { size: 10 } } },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => `${ctx.label}: R$ ${formatBrl(ctx.parsed)}`,
                        },
                    },
                },
            },
        });
    }
});
</script>

<template>
    <div class="px-4 py-4 space-y-4">
        <MonthPicker :transaction-month="month" :transaction-year="year" route="/" />

        <!-- Balance card -->
        <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-2xl p-5 text-white shadow-lg">
            <p class="text-sm opacity-80">Saldo do mês</p>
            <p class="text-3xl font-bold mt-1">{{ formatBrl(balance) }}</p>
            <div class="flex gap-6 mt-4">
                <div>
                    <p class="text-xs opacity-70">Receitas</p>
                    <p class="text-sm font-semibold">{{ formatBrl(totalIncome) }}</p>
                </div>
                <div>
                    <p class="text-xs opacity-70">Despesas</p>
                    <p class="text-sm font-semibold">{{ formatBrl(totalExpense) }}</p>
                </div>
            </div>
        </div>

        <!-- Budget alerts -->
        <div v-if="budgetAlerts.length" class="bg-amber-50 border border-amber-200 rounded-xl p-3 space-y-1">
            <p class="text-xs font-semibold text-amber-800 flex items-center gap-1">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                Orçamentos no limite
            </p>
            <p v-for="alert in budgetAlerts" :key="alert.id" class="text-xs text-amber-700">
                {{ alert.category.icon }} {{ alert.category.name }}: {{ alert.spent_percentage }}% usado
            </p>
        </div>

        <!-- Charts -->
        <div v-if="trendData" class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
            <h3 class="text-sm font-semibold text-gray-800 mb-3">Tendência (6 meses)</h3>
            <div class="h-48">
                <canvas ref="trendCanvas"></canvas>
            </div>
        </div>

        <div v-if="categoryData?.length" class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
            <h3 class="text-sm font-semibold text-gray-800 mb-3">Gastos por categoria</h3>
            <div class="h-48">
                <canvas ref="categoryCanvas"></canvas>
            </div>
        </div>

        <!-- Recent transactions -->
        <div>
            <div class="flex justify-between items-center mb-2 px-1">
                <h3 class="text-sm font-semibold text-gray-800">Transações recentes</h3>
                <Link :href="`/transactions?month=${month}&year=${year}`"
                      class="text-xs text-blue-600 font-medium">Ver todas</Link>
            </div>

            <div v-if="!recentTransactions.length" class="bg-white rounded-xl p-6 shadow-sm text-center">
                <p class="text-sm text-gray-500">Nenhuma transação neste mes.</p>
                <Link href="/transactions/create"
                      class="inline-block mt-2 text-sm text-blue-600 font-medium">Adicionar</Link>
            </div>

            <div v-else class="space-y-2">
                <TransactionCard v-for="transaction in recentTransactions"
                                 :key="transaction.id"
                                 :transaction="transaction" />
            </div>
        </div>
    </div>
</template>
