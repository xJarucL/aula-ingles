{{-- resources/views/partials/toast.blade.php --}}
@php
  $ok = session('auth_ok') ?? session('toast_ok') ?? null;
  $fail = session('auth_fail') ?? session('toast_fail') ?? null;
@endphp

@if ($ok || $fail)
  <style>
    .toast-wrap{position:fixed;top:18px;right:18px;z-index:9999;display:flex;gap:10px;flex-direction:column}
    .toast{border-radius:12px;padding:12px 14px;font-weight:600;box-shadow:0 8px 30px rgba(0,0,0,.12);opacity:.98}
    .toast-success{background:#e7f7ed;color:#166534;border:1px solid #86efac}
    .toast-error{background:#fdecec;color:#991b1b;border:1px solid #fecaca}
    .toast-close{margin-left:12px;cursor:pointer;font-weight:700;opacity:.6}
    .toast .row{display:flex;align-items:center;gap:10px}
  </style>
  <div class="toast-wrap" id="toastWrap">
    @if ($ok)
      <div class="toast toast-success"><div class="row">
        <span>{!! $ok !!}</span>
        <span class="toast-close" onclick="this.parentNode.parentNode.remove()">×</span>
      </div></div>
    @endif
    @if ($fail)
      <div class="toast toast-error"><div class="row">
        <span>{!! $fail !!}</span>
        <span class="toast-close" onclick="this.parentNode.parentNode.remove()">×</span>
      </div></div>
    @endif
  </div>
  <script>
    setTimeout(()=>{ const w=document.getElementById('toastWrap'); if(w) w.remove(); }, 4200);
  </script>
@endif
