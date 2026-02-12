; Music on Hold - Tenant {{ $tenant->code }}

[moh-{{ $tenant->code }}]
mode=files
directory=/var/lib/asterisk/moh/tenants/{{ $tenant->code }}
sort=alpha
