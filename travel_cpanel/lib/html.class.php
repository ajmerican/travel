<?php
	class HTML{
		public static $js;

		public static function toSelect($array, $options = array()){
			$options = array_merge(array(
				'val'			=> 'id',
				'text'			=> 'name',
				'first_blank'	=> 'yes',
				'id'			=> '',
				'attr'			=> '',
			), $options);

			$op	= '<select name="'.$options['name'].'" id="'.$options['id'].'" class="form-control select2" '.$options['attr'].'>';
			$op.= self::toOptions($array, $options);
			$op.= '</select>';
			return $op;
		}

		public static function toOptions($array, $options = array()){

			$options = array_merge(array(
				'val'			=> 'id',
				'text'			=> 'name',
				'first_blank'	=> 'yes',
			), $options);

			$op	= ($options['first_blank'] == 'yes') ? '<option value="">-- Select --</option>' : '';

			if(isset($array) && count($array) > 0){
				foreach($array as $key => $val){
					$selected	= ($options['selected'] == $val[$options['val']]) ? 'selected="selected"' : '';
					$op.= '<option value="'.$val[$options['val']].'" '.$selected.'>'.$val[$options['text']].'</option>';
				}
			}

			$op.= (isset($options['other']) && $options['other'] != '') ? '<option value="'.$options['other'].'">'.$options['other'].'</option>' : '';

			return $op;
		}

		public static function setJs($js = ''){
			self::$js.= $js;
		}

		public static function getJs($js = ''){
			return (isset(self::$js) && self::$js != '') ? '<script>'.self::$js.'</script>' : '';
		}
	}
