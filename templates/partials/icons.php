<?php
/** Inline SVG icons. Inline so there is no extra request and no icon font. */
declare(strict_types=1);

function icon(string $name, string $class = ''): string {
    $paths = [
        'shield'    => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/>',
        'mind'      => '<path d="M9.5 3A3.5 3.5 0 0 0 6 6.5 3 3 0 0 0 4 9.3a3 3 0 0 0 1 2.2A3 3 0 0 0 4 14a3 3 0 0 0 2.2 2.9A3.3 3.3 0 0 0 9.5 21c1.4 0 2.5-.9 2.5-2V5c0-1.1-1.1-2-2.5-2Z"/><path d="M14.5 3A3.5 3.5 0 0 1 18 6.5a3 3 0 0 1 2 2.8 3 3 0 0 1-1 2.2 3 3 0 0 1 1 2.5 3 3 0 0 1-2.2 2.9A3.3 3.3 0 0 1 14.5 21c-1.4 0-2.5-.9-2.5-2V5c0-1.1 1.1-2 2.5-2Z"/>',
        'scales'    => '<path d="M12 3v18"/><path d="M7 21h10"/><path d="M5 7h14"/><path d="M5 7 2 14h6L5 7Z"/><path d="M19 7l-3 7h6l-3-7Z"/>',
        'sprout'    => '<path d="M12 21V11"/><path d="M12 11C12 7 9 4 5 4c0 4 3 7 7 7Z"/><path d="M12 14c0-3.3 2.7-6 6-6 0 3.3-2.7 6-6 6Z"/>',
        'megaphone' => '<path d="M3 11v2a1 1 0 0 0 1 1h3l5 4V6L7 10H4a1 1 0 0 0-1 1Z"/><path d="M17 8a5 5 0 0 1 0 8"/><path d="M20 5a9 9 0 0 1 0 14"/>',
        'person'    => '<circle cx="12" cy="7" r="4"/><path d="M4 21c0-4 3.6-6 8-6s8 2 8 6"/>',
        'lock'      => '<rect x="4" y="10" width="16" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/>',
        'heart'     => '<path d="M12 21s-7.5-4.6-7.5-10A4.5 4.5 0 0 1 12 8a4.5 4.5 0 0 1 7.5 3c0 5.4-7.5 10-7.5 10Z"/>',
        'check'     => '<path d="M20 7 9.5 17.5 4 12"/>',
        'clock'     => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
        'phone'     => '<path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8.1 9.9a16 16 0 0 0 6 6l1.3-1.2a2 2 0 0 1 2.1-.5c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.7 2Z"/>',
        'whatsapp'  => '<path d="M21 11.5a8.4 8.4 0 0 1-9 8.4 8.5 8.5 0 0 1-3.8-.9L3 20.5l1.6-4.9A8.4 8.4 0 0 1 3.6 11 8.4 8.4 0 0 1 12 3a8.4 8.4 0 0 1 9 8.5Z"/>',
        'sms'       => '<rect x="5" y="2" width="14" height="20" rx="2"/><path d="M11 18h2"/>',
        'mail'      => '<rect x="2" y="4" width="20" height="16" rx="2"/><path d="m2 7 10 6L22 7"/>',
        'pin'       => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/>',
        'chat'      => '<path d="M4 4h16v12H7l-3 3V4Z"/>',
        'warning'   => '<path d="M12 9v4"/><path d="M12 17h.01"/><path d="M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0Z"/>',
        'info'      => '<circle cx="12" cy="12" r="9"/><path d="M12 8v5"/><path d="M12 16h.01"/>',
        'menu'      => '<path d="M3 6h18M3 12h18M3 18h18"/>',
        'exit'      => '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="m16 17 5-5-5-5"/><path d="M21 12H9"/>',
        'picture'   => '<rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="8.5" cy="9.5" r="1.5"/><path d="m21 15-4.5-4.5L11 16l-2-2-5 5"/>',
    ];
    $d = $paths[$name] ?? $paths['info'];
    $c = $class !== '' ? ' class="' . e($class) . '"' : '';
    return '<svg' . $c . ' viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $d . '</svg>';
}

function icon_names(): array {
    return ['shield', 'mind', 'scales', 'sprout', 'megaphone', 'person', 'lock', 'heart', 'check', 'clock'];
}

/** The braided brand mark. */
function brand_mark(string $class = 'mark', string $colour = '#9C2B2E'): string {
    return '<svg class="' . e($class) . '" viewBox="0 0 64 64" aria-hidden="true" focusable="false">
        <g fill="none" stroke="' . $colour . '" stroke-width="5" stroke-linecap="round">
          <path d="M12 5C12 19 52 19 52 32S12 45 12 59"/>
          <path d="M52 5C52 19 12 19 12 32S52 45 52 59" stroke-opacity=".42"/>
        </g>
      </svg>';
}
