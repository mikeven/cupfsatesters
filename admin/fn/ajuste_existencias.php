<tr class="target_ajinv" id="trg<?php echo $item['idItem']?>">
	<td colspan="6" style="padding: 2;" align="right">

		<table>
			<tr class="innerfila">
				<td align="right" style="padding: 2px;">
					<div style="font-size:10px; margin:2px">RESTAR EXISTENCIAS</div>
				</td>
				<td style="padding: 2px;">
					<div>
						<select id="e<?php echo $item['idItem']?>" name="cantidad" class="ajuste_existencias_inv">
							<?php for( $i = 0; $i <= $top_u; $i++ ) { ?>
								<option value="<?php echo $i?>"><?php echo $i?></option>
							<?php } ?>
						</select>
						<a href="#!" class="ex_ajusteinv" data-item="<?php echo $item['idItem']?>"> 
							<i class="fas fa-chevron-circle-right fa-2x bot_cnfajuste" title="Guardar"></i>
						</a>
						<a href="#!" class="opc_ajuste_inv" data-t="trg<?php echo $item['idItem']?>"> 
							<i class="fas fa-times fa-2x" title="Cancelar" style="vertical-align: bottom;"></i>
						</a>
					</div>
				</td>
			</tr>
		</table>

	</td>
	
</tr>