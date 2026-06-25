<div
    x-data="{ show:false, message:'', type:'success' }"
    x-on:notify.window="
        show = true;
        message = $event.detail.message;
        type = $event.detail.type;
        setTimeout(() => show = false, 3000);
    "
    x-show="show"
    x-transition
    class="fixed top-5 right-5 z-50"
>
    <div
        class="px-6 py-4 rounded-xl shadow-lg text-white"
        :class="{
            'bg-green-600': type === 'success',
            'bg-red-600': type === 'error'
        }"
    >
        <span x-text="message"></span>
    </div>
</div>
