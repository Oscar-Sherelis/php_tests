<?php

// Read JSON file
$json = file_get_contents('json/input.json');

//Decode JSON
$json_data = json_decode($json, true);

$sum = 0;
$starting_index = 0;
$reuslt = [];

// checks how many elements added
$added_el_counter = 0;

foreach ($json_data as $data_key => $data) {

    // two array sizes to go from highest to lowest element
    $all_size = count($data['sms_list']) - 1;
    $arr_size = count($data['sms_list']) - 1;

    $max_messages = $data['max_messages'];

    // "sms-list" array with values 'price' 'income'
    foreach ($data as $value_key => $value) {

        if (is_array($value)) {
            foreach ($value as $inside_val_key => $inside_val) {

                // checking if our highest value can be added to sum as last element
                if ($sum + $value[$all_size]['income'] >= $data['required_income'] && $added_el_counter < $max_messages) {
                    if ($sum + $value[$starting_index]['income'] > $data['required_income']) {

                        $sum += $value[$starting_index]['income'];
                        $reuslt[] = $value[$starting_index]['price'];
                        $added_el_counter++;
                        break;

                    } else {
                        // starting from lowest value value[$starting_index] and going to higher until it is enought
                        while ($sum + $value[$starting_index]['income'] < $data['required_income']) {
                            $value[$starting_index++]['income'];
                        }

                        $sum += $value[$starting_index]['income'];
                        $reuslt[] = $value[$starting_index]['price'];
                        $added_el_counter++;
                        break;
                    }

                    // to check cheap way we must check 2 values before index >= 2, otherwise it will not work
                    if ($starting_index >= 2) {

                        $minus_two = $value[$starting_index - 2]['income'];
                        $minus_one = $value[$starting_index - 1]['income'];

                        $cheap = $minus_two + $minus_one;

                        // if can make lower price
                        if ($cheap < $value[$starting_index]['income'] && $cheap + $sum >= $data['required_income'] && $max_messages - $added_el_counter >= 2) {

                            if ($starting_index > 2) {
                                $minus_three = $value[$starting_index - 3]['income'];
                                $lower_cheap = $minus_three + $minus_one;

                                // if can take lower value take it
                                if ($lower_cheap + $sum >= $data['required_income'] && $lower_cheap < $cheap) {
                                    $sum += $lower_cheap;
                                    $reuslt[] = $value[$starting_index - 1]['price'];
                                    $reuslt[] = $value[$starting_index - 3]['price'];
                                    $added_el_counter++;
                                    break;

                                    // if cannot take lower value
                                }
                            } else {
                                $sum += $cheap;
                                $reuslt[] = $value[$starting_index - 1]['price'];
                                $reuslt[] = $value[$starting_index - 2]['price'];
                                $added_el_counter++;
                                break;
                            }

                        } else {
                            $sum += $value[$starting_index]['income'];
                            $reuslt[] = $value[$starting_index]['price'];
                            $added_el_counter++;
                            break;
                        }
                    }

                    // if sum + val < required income. Add value
                }
                // if number of messages is not enought
                if ($sum < $data['required_income'] && $added_el_counter == $max_messages) {
                    print $need_more_message = $data['max_messages'] . ' max_mesages is not enought ';
                    break;
                } else {

                    if ($sum + $value[$arr_size]['income'] < $data['required_income']) {

                        while ($sum + $value[$arr_size]['income'] < $data['required_income'] && $added_el_counter < $max_messages) {
                            $sum += $value[$arr_size]['income'];
                            $reuslt[] = $value[$arr_size]['price'];
                            $added_el_counter++;
                        }

                    } else {
                        $arr_size--;
                    }
                }
            }
        }
    }
}

var_dump($json_data[0]);
print json_encode($reuslt, JSON_PRETTY_PRINT);
