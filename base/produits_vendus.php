<?php
/**
 * Déclarations relatives à la base de données
 *
 * @plugin     produits_vendus
 * @copyright  2016
 * @author     Arnaud B. (Mist. GraphX) - http://www.mister-graphx.com
 * @licence    GNU/GPL
 * @package    SPIP\ProduitsVendus\Pipelines
 */

if (!defined('_ECRIRE_INC_VERSION')) return;


/**
 * Déclarer le nouveau statut sur les produits
 *
 * @param array $tables
 * 		Description des tables
 * @return array
 * 		Description complétée des tables
 */
function produits_vendus_declarer_tables_objets_sql($tables){


	array_set_merge($tables, 'spip_produits', array(
		'statut_textes_instituer'=> array(
            'prepa'    => 'texte_statut_en_cours_redaction',
			'prop'     => 'texte_statut_propose_evaluation',
			'publie'   => 'texte_statut_publie',
			'refuse'   => 'texte_statut_refuse',
			'poubelle' => 'texte_statut_poubelle',
            'produit_vendu'     => 'produits_vendus:texte_statut_vendu'
		),
        'statut'=> array(
			array(
				'champ'     => 'statut',
				'publie'    => 'publie,produit_vendu',
				'previsu'   => 'publie,prop,prepa,produit_vendu',
				'post_date' => 'date', 
				'exception' => array('statut','tout')
			)
		),
        'statut_images' => array(
                'prepa'    => '../prive/themes/spip/images/puce-preparer-8.png',
                'prop'     => '../prive/themes/spip/images/puce-proposer-8.png',
                'publie'   => '../prive/themes/spip/images/puce-publier-8.png',
                'refuse'   => '../prive/themes/spip/images/puce-refuser-8.png',
                'poubelle' => '../prive/themes/spip/images/puce-supprimer-8.png',
                'produit_vendu' => '../prive/themes/spip/images/puce-produit_vendu-8.png',
        )
	));

	return $tables;
}
