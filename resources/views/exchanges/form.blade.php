<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Create new api') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("You can create as many api keys as you need, there is no limitation.") }}
            <br />
            {{ __("For security reasons dont enable transfers on your apis.") }}
        </p>
    </header>
    <form wire:submit.prevent="submit" class="mt-6 space-y-6">
        <div class="grid grid-cols-3 grid-flow-col gap-4 mb-6">
            <div>
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" type="text" class="mt-1 block w-full" wire:model.lazy="exchange.name" required autofocus />
                <x-input-error class="mt-2" :messages="$errors->get('exchange.name')" />
            </div>
            <div>
                <x-input-label for="exchange" :value="__('Exchange')" />
                <x-select-input id="exchange" type="text" class="mt-1 block w-full" wire:model="exchange.exchange" required>
                    @foreach ($exchanges as $key => $value)
                        <option value="{{$key}}">{{$value}}</option>
                    @endforeach
                </x-select-input>
                <x-input-error class="mt-2" :messages="$errors->get('exchange.exchange')" />
            </div>
            <div>
                <x-input-label for="risk_mode" :value="__('Risk Mode')" />
                <x-select-input id="risk_mode" type="text" class="mt-1 block w-full" wire:model="exchange.risk_mode" required>
                    @foreach ($risk_modes as $key => $value)
                        <option value="{{$key}}">{{$value}}</option>
                    @endforeach
                </x-select-input>
                <x-input-error class="mt-2" :messages="$errors->get('exchange.risk_mode')" />
            </div>
        </div>

        @if (!isset($on_edit))
            <div class="grid grid-cols-3 grid-flow-col gap-4 mb-6">
                <div>
                    <x-input-label for="api_key" :value="__('Api key')" />
                    <x-text-input id="api_key" type="text" class="mt-1 block w-full" wire:model.lazy="exchange.api_key" required />
                    <x-input-error class="mt-2" :messages="$errors->get('exchange.api_key')" />
                </div>
                <div>
                    <x-input-label for="api_secret" :value="__('Api secret')" />
                    <x-text-input id="api_secret" type="text" class="mt-1 block w-full" wire:model.lazy="exchange.api_secret" required />
                    <x-input-error class="mt-2" :messages="$errors->get('exchange.api_secret')" />
                </div>
            </div>
        @else
            <span class="text-md text-gray-800 dark:text-gray-400">
                <span class="font-bold">API Key:</span> <span class="mr-3">{{$exchange->api_key}}</span>
                <br />
                <span class="font-bold">API password:</span> <span class="mr-3">{{ Str::mask($exchange->api_secret, '*', 3, strlen($exchange->api_secret) - 6); }}</span>
            </span>

        @endif

        <div class="flex items-center gap-4">
            <x-primary-button>{{ isset($on_edit) ? __('Update exchange') : __('Create exchange') }}</x-primary-button>

            @if (session('status') === 'exchange-created' || session('status') === 'exchange-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 4000)"
                    class="text-sm text-green-600 dark:text-green-400"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
