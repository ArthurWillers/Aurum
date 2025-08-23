<div>
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Moeda fixa: Real (R$)')" :subheading="__('Todos os valores serão exibidos em R$')">
        <div class="p-4 text-green-700">A moeda do sistema agora é fixa em Real (R$).</div>
    </x-settings.layout>
</div>
