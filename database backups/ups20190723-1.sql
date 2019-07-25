SELECT `raw_materials_imports`.`raw_materials_imports_id`,
    `raw_materials_imports`.`rmi_no`,
    `raw_materials_imports`.`material_id`,
    `raw_materials_imports`.`pi_no`,
    `raw_materials_imports`.`pi_date`,
    `raw_materials_imports`.`supplier`,
    `raw_materials_imports`.`manufacturer`,
    `raw_materials_imports`.`qty`,
    `raw_materials_imports`.`amount`,
    `raw_materials_imports`.`exp_date_shipment`,
    `raw_materials_imports`.`remarks_order`,
    `raw_materials_imports`.`user_order`,
    `raw_materials_imports`.`bill_no`,
    `raw_materials_imports`.`date_shipment`,
    `raw_materials_imports`.`invoice_no`,
    `raw_materials_imports`.`delay_sent`,
    `raw_materials_imports`.`terms`,
    `raw_materials_imports`.`exp_date_arrival`,
    `raw_materials_imports`.`bill_due_date`,
    `raw_materials_imports`.`remarks_shipped`,
    `raw_materials_imports`.`user_shipped`,
    `raw_materials_imports`.`date_cleared`,
    `raw_materials_imports`.`qty_cleared`,
    `raw_materials_imports`.`date_cleared2`,
    `raw_materials_imports`.`qty_cleared2`,
    `raw_materials_imports`.`delay_arrived`,
    `raw_materials_imports`.`declaration_no`,
    `raw_materials_imports`.`damaged_qty`,
    `raw_materials_imports`.`usd_rate`,
    `raw_materials_imports`.`duty`,
    `raw_materials_imports`.`clearing`,
    `raw_materials_imports`.`unloading`,
    `raw_materials_imports`.`cost_kg`,
    `raw_materials_imports`.`remarks_cleared`,
    `raw_materials_imports`.`user_cleared`,
    `raw_materials_imports`.`bank_letter_date`,
    `raw_materials_imports`.`paid_date`,
    `raw_materials_imports`.`delay_payment`,
    `raw_materials_imports`.`remarks_paid`,
    `raw_materials_imports`.`user_paid`,
    `raw_materials_imports`.`status`
FROM `ups_db`.`raw_materials_imports`;
