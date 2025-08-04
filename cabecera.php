        <div class="cabecera">
        	<table width="100%">
        		<tr>
        			<td>
<!-- Dinámico 
						<img class="logo-people-experts" src="{!! asset("/img/people_experts_logo.jpg") !!}">
-->
            			<img class="logo-people-experts" src="img/people_experts_logo.jpg">
        			</td>
        			<td>
<!-- Dinámico
						<img class="logo-cliente" src="img/{{ $clients->logo }}">
-->
			            <img class="logo-cliente" src="img/logo-cliente.jpg">
        			</td>
        		</tr>
        	</table>
<!-- Dinámico
			<div class="intro-sup" style="background-color: {{ $clients->colorSeccion1 }} !important;color: {{ $clients->colorFontSeccion1 }} !important;">Informe {{ $evaluation->name }}</div>
-->
            <div class="intro-sup" style="background-color: #666 !important;color: #fff !important;">{{ $evaluation->name }}</div>
        </div>
