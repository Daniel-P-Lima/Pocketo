<script setup>
import { computed, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
    transaction: {
        type: Object,
        required: true,
    },
    expenseCategories: {
        type: Array,
        default: () => [],
    },
    incomeCategories: {
        type: Array,
        default: () => [],
    },
})

// ── Form state (Inertia) ─────────────────────────────────────────────────────
// useForm seeds initial values and gives us .errors, .processing, .put() etc.
const form = useForm({
    type: props.transaction.type ?? 'expense',
    amount: props.transaction.amount ?? '',
    description: props.transaction.description ?? '',
    category_id: props.transaction.category_id ?? null,
    date: props.transaction.date ?? '',
    notes: props.transaction.notes ?? '',
})

const displayAmount = computed(() => {
    if (!form.amount && form.amount !== 0) return '';
    const num = parseFloat(form.amount);
    if (isNaN(num)) return '';
    return num.toLocaleString('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });
})

function handleAmountInput(event) {
    const raw = event.target.value.replace(/[^\d,]/g, '').replace(',', '.')
    form.amount = raw === '' ? '' : parseFloat(raw) || ''
}

const currentCategories = computed(() =>
    form.type === 'income' ? props.incomeCategories : props.expenseCategories
)

watch(() => form.type, () => {
    form.category_id = null
})

function handleSubmit() {
    form.put(`/transactions/${props.transaction.id}`)
}
</script>

<template>
    <div class="edit-transaction">
        <form @submit.prevent="handleSubmit" class="form-body">

            <!-- Type Toggle -->
            <div class="type-toggle">
                <button type="button" :class="['toggle-btn', 'expense', { active: form.type === 'expense' }]"
                    @click="form.type = 'expense'">
                    Despesa
                </button>
                <button type="button" :class="['toggle-btn', 'income', { active: form.type === 'income' }]"
                    @click="form.type = 'income'">
                    Receita
                </button>
            </div>

            <!-- Amount -->
            <div class="field">
                <label class="field-label">Valor</label>
                <div class="amount-wrapper">
                    <span class="currency-prefix">R$</span>
                    <input type="text" inputmode="numeric" class="amount-input" placeholder="0,00"
                        :value="displayAmount" @input="handleAmountInput" />
                </div>
                <p v-if="form.errors.amount" class="field-error">{{ form.errors.amount }}</p>
            </div>

            <!-- Description -->
            <div class="field">
                <label class="field-label">Descrição</label>
                <input type="text" v-model="form.description" required maxlength="255" class="text-input" />
                <p v-if="form.errors.description" class="field-error">{{ form.errors.description }}</p>
            </div>

            <!-- Category -->
            <div class="field">
                <label class="field-label">Categoria</label>
                <div class="categories-grid">
                    <label v-for="cat in currentCategories" :key="cat.id" class="category-item"
                        :class="{ selected: form.category_id == cat.id }">
                        <input type="radio" name="category_id" :value="cat.id" v-model="form.category_id"
                            class="sr-only" />
                        <span class="cat-icon">{{ cat.icon }}</span>
                        <span class="cat-name">{{ cat.name }}</span>
                    </label>
                </div>
                <p v-if="form.errors.category_id" class="field-error">{{ form.errors.category_id }}</p>
            </div>

            <!-- Date -->
            <div class="field">
                <label class="field-label">Data</label>
                <input type="date" v-model="form.date" required class="text-input" />
                <p v-if="form.errors.date" class="field-error">{{ form.errors.date }}</p>
            </div>

            <!-- Notes -->
            <div class="field">
                <label class="field-label">Observações <span class="optional">(opcional)</span></label>
                <textarea v-model="form.notes" rows="2" maxlength="1000" class="textarea-input" />
            </div>

            <button type="submit" class="submit-btn" :disabled="form.processing">
                {{ form.processing ? 'Salvando...' : 'Atualizar' }}
            </button>
        </form>
    </div>
</template>

<style scoped>
.edit-transaction {
    padding: 1rem;
}

.form-body {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}

/* ── Type Toggle ── */
.type-toggle {
    display: flex;
    gap: 0.5rem;
}

.toggle-btn {
    flex: 1;
    padding: 0.75rem;
    border-radius: 0.75rem;
    border: 1.5px solid #d1d5db;
    background: white;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.15s, color 0.15s, border-color 0.15s;
}

.toggle-btn.expense.active {
    background: #ef4444;
    color: white;
    border-color: #ef4444;
}

.toggle-btn.income.active {
    background: #10b981;
    color: white;
    border-color: #10b981;
}

.toggle-btn:not(.active):active {
    background: #f3f4f6;
}

/* ── Fields ── */
.field {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.field-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
}

.optional {
    font-weight: 400;
    color: #9ca3af;
}

.field-error {
    font-size: 0.75rem;
    color: #ef4444;
    margin: 0;
}

/* ── Amount ── */
.amount-wrapper {
    position: relative;
}

.currency-prefix {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #6b7280;
    font-size: 0.875rem;
    pointer-events: none;
}

.amount-input {
    width: 100%;
    background: white;
    border: 1.5px solid #d1d5db;
    border-radius: 0.75rem;
    padding: 0.75rem 1rem 0.75rem 2.75rem;
    font-size: 1.125rem;
    font-weight: 600;
    box-sizing: border-box;
    outline: none;
    transition: border-color 0.15s, box-shadow 0.15s;
}

.amount-input:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
}

/* ── Text inputs ── */
.text-input,
.textarea-input {
    width: 100%;
    background: white;
    border: 1.5px solid #d1d5db;
    border-radius: 0.75rem;
    padding: 0.75rem 1rem;
    font-size: 0.875rem;
    box-sizing: border-box;
    outline: none;
    transition: border-color 0.15s, box-shadow 0.15s;
    font-family: inherit;
}

.text-input:focus,
.textarea-input:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
}

.textarea-input {
    resize: none;
}

/* ── Categories ── */
.categories-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 0.5rem;
}

.category-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.25rem;
    padding: 0.625rem 0.25rem;
    border-radius: 0.75rem;
    border: 1.5px solid #e5e7eb;
    cursor: pointer;
    transition: border-color 0.15s, box-shadow 0.15s;
}

.category-item:active {
    background: #f9fafb;
}

.category-item.selected {
    border-color: #3b82f6;
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.3);
}

.cat-icon {
    font-size: 1.25rem;
    line-height: 1;
}

.cat-name {
    font-size: 0.7rem;
    color: #4b5563;
    text-align: center;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    width: 100%;
    padding: 0 2px;
}

/* ── Submit ── */
.submit-btn {
    width: 100%;
    background: #2563eb;
    color: white;
    font-weight: 600;
    padding: 0.875rem;
    border-radius: 0.75rem;
    border: none;
    cursor: pointer;
    font-size: 1rem;
    transition: background 0.15s;
}

.submit-btn:disabled {
    background: #93c5fd;
    cursor: not-allowed;
}


/* Screen-reader only (replaces Tailwind sr-only) */
.sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border-width: 0;
}
</style>