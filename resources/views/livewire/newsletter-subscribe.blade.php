<div>
    @if($successMessage)
        <div class="mb-3 rounded-md bg-green-500/10 border border-green-500/30 p-3 text-sm text-green-200 animate-in fade-in slide-in-from-top-2 duration-300">
            {{ $successMessage }}
        </div>
    @endif

    @if($errorMessage)
        <div class="mb-3 rounded-md bg-red-500/10 border border-red-500/30 p-3 text-sm text-red-200 animate-in fade-in slide-in-from-top-2 duration-300">
            {{ $errorMessage }}
        </div>
    @endif

    <form wire:submit.prevent="subscribe" class="flex gap-2">
        <div class="flex-1">
            <input 
                type="email" 
                wire:model="email"
                class="flex h-10 w-full rounded-md border px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm bg-primary-foreground/10 border-primary-foreground/20 text-primary-foreground placeholder:text-primary-foreground/60"
                placeholder="Votre email"
                required
            >
            @error('email') 
                <span class="text-xs text-red-300 mt-1 block">{{ $message }}</span> 
            @enderror
        </div>
        <button 
            type="submit"
            wire:loading.attr="disabled"
            class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0 bg-secondary text-secondary-foreground hover:bg-secondary-light shadow-glow hover:shadow-elegant transition-smooth h-11 px-6 py-3">
            <span wire:loading.remove>S'abonner</span>
            <span wire:loading>
                <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </span>
        </button>
    </form>
</div>
