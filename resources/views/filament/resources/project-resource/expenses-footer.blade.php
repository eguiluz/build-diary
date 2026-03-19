<div class="border-t border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900">
    <div class="flex flex-wrap justify-end gap-6">
        <div class="text-right">
            <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">{{ __('app.filament.expenses_footer.total_budget') }}</p>
            <p class="text-lg font-bold text-gray-900 dark:text-white">{{ number_format($totalBudget, 2, ',', '.') }} €</p>
        </div>
        <div class="text-right">
            <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">{{ __('app.filament.expenses_footer.spent') }}</p>
            <p class="text-lg font-bold text-green-600 dark:text-green-400">{{ number_format($spentBudget, 2, ',', '.') }} €</p>
        </div>
        <div class="text-right">
            <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">{{ __('app.filament.expenses_footer.pending') }}</p>
            <p class="text-lg font-bold text-amber-600 dark:text-amber-400">{{ number_format($pendingBudget, 2, ',', '.') }} €</p>
        </div>
    </div>
</div>
