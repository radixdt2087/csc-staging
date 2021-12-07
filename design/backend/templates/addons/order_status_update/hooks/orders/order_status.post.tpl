<a href="https://myiq.digitzs.com/login" target="_blank">Customer Refunds</a>
{if $txnStatus}
	{literal}
		<script language="JavaScript" type="text/javascript">
		let attribute = document.getElementsByClassName('control-group');
		for (let i = 0; i < attribute.length; i++) {
			let impDiv = attribute[i];
			if(impDiv.firstElementChild){
				let value = impDiv.firstElementChild.innerHTML.trim();
				if (value == 'Order status') {
					let nextElement = impDiv.firstElementChild;
					nextElement.style.display = 'none';
					nextElement.nextElementSibling.style.display = 'none';
				}
			}
		}
		</script>
	{/literal}
{/if}