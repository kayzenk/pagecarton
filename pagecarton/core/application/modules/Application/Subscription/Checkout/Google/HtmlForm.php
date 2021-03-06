<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Subscription_Checkout_Google_HtmlForm
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: HtmlForm.php 5.7.2012 11.53 ayoola $
 */

/**
 * @see Application_Subscription_Abstract
 */
 
require_once 'Application/Subscription/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Subscription_Checkout_Google_HtmlForm
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Subscription_Checkout_Google_HtmlForm extends Application_Subscription_Checkout_Abstract_HtmlForm
{

    /**
     * Form Action
     * 
     * @var string
     */
	protected static $_formAction = 'https://checkout.google.com/api/checkout/v2/checkoutForm/Merchant/241634264157584';
	
    /**
     * The Mechant ID
     * 
     * @var string
     */
	protected $_mechantId = '241634264157584';
	
    /**
     * The Mechant Key
     * 
     * @var string
     */
	protected $_mechantKey = 't0WlrtpiujC44szqrx6zxQ';
			
    /**
     * Whitelist and blacklist of currencies
     * 
     * @var array
     */
	protected static $_currency= array( 'whitelist' => 'USD', 'blacklist' => 'NGN' );
		
    /**
     * Creates the request
     * 
     * @param void
     * @return array
     */
	protected static function buildRequest()
    {
		if( ! $cart = self::getStorage()->retrieve() ){ return; }
		$values = $cart['cart'];
		
		//	Initialize array for the POST parameters
		$parameters = static::getDefaultParameters();
		$parameters['notify_url'] = $parameters['notify_url'];
		$parameters['success_url'] = $parameters['success_url'];
		$parameters['fail_url'] = $parameters['fail_url'];
		$parameters['total'] = $parameters['total'];
		$parameters['v_merchant_id'] = self::$_merchantId;
		$parameters['merchant_ref'] = $parameters['order_number'];
		$parameters['edit_url'] = $cart['settings']['return_url'];
		$parameters['continue_url'] = $cart['settings']['return_url'];
		$parameters['_charset_'] = null;
		
		$counter = 1;
		foreach( $values as $name => $value )
		{
			if( ! isset( $value['price'] ) )
			{
				$value = array_merge( self::getPriceInfo( $value['price_id'] ), $value );
			}
			$parameters['item_name_' . $counter] = $value['subscription_name'];
			@$parameters['item_description_' . $counter] = $value['item-description'];
			$parameters['item_price_' . $counter] = $value['price'];
			$parameters['item_quantity_' . $counter] = $value['multiple'];
			$parameters['item_merchant_id_' . $counter] = $value['price_id'];
			$parameters['item_merchant_id_' . $counter] = $value['price_id'];
			$parameters['item_currency_' . $counter] = $cart['settings']['currency_abbreviation'];
			$parameters['total'] += $value['price'];
			$counter++;
		}
	//	$form = new Ayoola_Form();
	//	var_export( self::getObjectName( __CLASS__ ) );
	//	var_export( $values );
		return $parameters;
    } 
	// END OF CLASS
}
