; ======================================================================
; Dialplan do tenant-{{ $tenant->code }}
; Gerado automaticamente via Filament - {{ now()->format('d/m/Y H:i') }}
; ======================================================================

; --- PERFIS DE DISCAGEM (SAÍDA) ---

; Perfil 1: Somente Interno
[ctx-{{ $tenant->code }}-from-internal]
#include "tenants/{{ $tenant->code }}/dialplan/internal.conf"

; Perfil 2: Fixo Local (Herda o Interno)
[ctx-{{ $tenant->code }}-local]
include => ctx-{{ $tenant->code }}-from-internal
#include "tenants/{{ $tenant->code }}/dialplan/external_local.conf"

; Perfil 3: Completo (Herda o Local)
[ctx-{{ $tenant->code }}-full]
include => ctx-{{ $tenant->code }}-local
#include "tenants/{{ $tenant->code }}/dialplan/external_full.conf"

; --- PONTOS DE ENTRADA ESPECÍFICOS ---

; Entrada de chamadas externas (Vem do Tronco/DID)
[ctx-{{ $tenant->code }}-incoming]
#include "tenants/{{ $tenant->code }}/dialplan/incoming.conf"

; Ponto de entrada genérico do Tenant (se precisar de um redirecionamento fixo)
[from-tenant-{{ $tenant->code }}]
exten => _X.,1,NoOp(Chamada vinda do Tenant {{ $tenant->code }})
 same => n,Goto(ctx-{{ $tenant->code }}-from-internal,${EXTEN},1)
