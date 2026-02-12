; Dialplan Interno - Tenant {{ $tenant->code }}
[ctx-{{ $tenant->code }}-from-internal]
; --- Gravação e Identificação (Regras Fixas) ---
exten => _X.,1,NoOp(Chamada de ${CALLERID(num)} para ${EXTEN} no tenant {{ $tenant->code }})
 same => n,Set(CDR(accountcode)={{ $tenant->code }})
 same => n,Set(REC_PATH=/var/spool/asterisk/monitor/tenants/{{ $tenant->code }}/${STRFTIME(${EPOCH},,%Y/%m/%d)})
 same => n,Set(REC_NAME=${STRFTIME(${EPOCH},,%H%M%S)}-${CALLERID(num)}-${EXTEN}-${UNIQUEID}.wav)
 same => n,System(mkdir -p ${REC_PATH})
 same => n,MixMonitor(${REC_PATH}/${REC_NAME},ab)
 same => n,Goto(ctx-{{ $tenant->code }}-internal-realtime,${EXTEN},1)

; --- Contexto Realtime para Ramais (Isolado por Tenant) ---
; O Asterisk vai procurar na tabela 'extensions' por uma entrada com o contexto 'ctx-TENANTCODE-internal-realtime'
; e o ramal discado. A ação (Dial) é criada pelo ExtensionObserver.
[ctx-{{ $tenant->code }}-internal-realtime]
switch => Realtime/ctx-{{ $tenant->code }}-internal-realtime@extensions
