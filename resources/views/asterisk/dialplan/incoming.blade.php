; Dialplan de Entrada - Tenant {{ $tenant->code }}
; Contexto usado pelos Troncos PJSIP

[ctx-{{ $tenant->code }}-incoming]
@foreach ($did_numbers as $did)
; Número Externo: {{ $did->number }} -> Destino: {{ $did->destination }}
exten => {{ $did->number }},1,NoOp(Entrada via DID {{ $did->number }})
 same => n,Set(CDR(accountcode)={{ $tenant->code }})
 ; Aqui a lógica depende do destino definido no seu painel
 same => n,Goto(ctx-{{ $tenant->code }}-internal,{{ $did->destination }},1)
@endforeach
