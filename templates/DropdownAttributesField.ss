<select $AttributesHTML>
<% loop $Options %>
	<option value="$Value.XML"<% if $Selected %> selected="selected"<% end_if %><% if $Disabled %> disabled="disabled"<% end_if %> $OptionAttributesHTML>$Title.XML</option>
<% end_loop %>
</select>
