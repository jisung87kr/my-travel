@props([
    'target' => 'main-content',
    'text' => '본문 바로가기',
])

<a
    href="#{{ $target }}"
    {{ $attributes->merge(['class' => 'skip-link']) }}
    @click.prevent="skipToContent('{{ $target }}')"
>
    {{ $text }}
</a>

<script>
function skipToContent(targetId) {
    const target = document.getElementById(targetId);

    if (!target) {
        console.warn(`Skip link target "${targetId}" not found`);
        return;
    }

    // Scroll to target
    target.scrollIntoView({ behavior: 'smooth', block: 'start' });

    // Focus the target
    target.setAttribute('tabindex', '-1');
    target.focus();

    // Remove tabindex after focus
    target.addEventListener('blur', () => {
        target.removeAttribute('tabindex');
    }, { once: true });
}
</script>

<style>
.skip-link {
    position: absolute;
    top: -40px;
    left: 0;
    z-index: 100;
    padding: 0.5rem 1rem;
    background-color: #FF385C;
    color: white;
    text-decoration: none;
    font-weight: 600;
    border-radius: 0 0 0.5rem 0;
    transition: top 0.2s;
}

.skip-link:focus {
    top: 0;
    outline: 2px solid #E31C5F;
    outline-offset: 2px;
}
</style>
