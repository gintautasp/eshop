-- užsakymų informacija

SELECT 
	`uzsakovai`.`pav`
    , `uzsakovai`.`email`
    , `uzsakymai`.`data`
    , `prekes`.`pav`
    , `uzsakymai_prekes`.`kiekis`
    , `uzsakymai_prekes`.`kaina_prekes` AS `kaina`
FROM 
`uzsakymai`
LEFT JOIN `uzsakymai_prekes` ON (
    `uzsakymai`.`id`=`uzsakymai_prekes`.`id_uzsakymo`
) 
LEFT JOIN `prekes` ON (
    	`uzsakymai_prekes`.`id_prekes`=`prekes`.`id`
    )
LEFT JOIN `uzsakovai` ON (
    	`uzsakymai`.`id_uzsakovo`=`uzsakovai`.`id`
)
WHERE 1`
ORDER BY
	`uzsakymai`.`data` DESC;
	
-- atsakaita pagal užsakymus
	
SELECT 
	`uzsakovai`.`pav`
    , `uzsakovai`.`email`
    , `uzsakymai`.`data`
    , GROUP_CONCAT(DISTINCT `prekes`.`pav`) AS `prekes`
    , SUM(`uzsakymai_prekes`.`kiekis`*`uzsakymai_prekes`.`kaina_prekes`) AS `uzsakymo_suma`
FROM 
`uzsakymai`
LEFT JOIN `uzsakymai_prekes` ON (
    `uzsakymai`.`id`=`uzsakymai_prekes`.`id_uzsakymo`
) 
LEFT JOIN `prekes` ON (
    	`uzsakymai_prekes`.`id_prekes`=`prekes`.`id`
    )
LEFT JOIN `uzsakovai` ON (
    	`uzsakymai`.`id_uzsakovo`=`uzsakovai`.`id`
)
WHERE 1
GROUP BY
	`uzsakymai`.`id`
ORDER BY
	`uzsakymai`.`data` DESC;

-- ataskaita pagal pirkėją

SELECT 
	`uzsakovai`.`pav`
    , `uzsakovai`.`email`
    , MIN(`uzsakymai`.`data`) AS `pirmas_pirkimas`
    , MAX(`uzsakymai`.`data`) AS `paskutinis_pirkimas`
    , COUNT(DISTINCT `uzsakymai`.`id`) AS `uzsakymu_kiekis`
    , GROUP_CONCAT(DISTINCT `prekes`.`pav`) AS `prekes`
    , SUM(`uzsakymai_prekes`.`kiekis`*`uzsakymai_prekes`.`kaina_prekes`) AS `uzsakymo_suma`
FROM 
`uzsakymai`
LEFT JOIN `uzsakymai_prekes` ON (
    `uzsakymai`.`id`=`uzsakymai_prekes`.`id_uzsakymo`
) 
LEFT JOIN `prekes` ON (
    	`uzsakymai_prekes`.`id_prekes`=`prekes`.`id`
    )
LEFT JOIN `uzsakovai` ON (
    	`uzsakymai`.`id_uzsakovo`=`uzsakovai`.`id`
)
WHERE 1
GROUP BY
	`uzsakovai`.`email`
ORDER BY
	`uzsakymai`.`data` DESC;