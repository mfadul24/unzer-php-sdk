<?php
/**
 * This is the success page for the example payments.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @copyright Copyright © 2016-present heidelpay GmbH. All rights reserved.
 *
 * @link  http://dev.heidelpay.com/
 *
 * @author  Simon Gabriel <development@heidelpay.com>
 *
 * @package  heidelpay/mgw_sdk/examples
 */
use heidelpay\MgwPhpSdk\Heidelpay;

/** Require the constants of this example */
require_once __DIR__ . '/Constants.php';

/** Require the composer autoloader file */
require_once __DIR__ . '/../../../autoload.php';

include 'assets/partials/_phpHead.php';
?>

<!DOCTYPE html>
<html lang="en">

    <?php include 'assets/partials/_htmlHead.php'; ?>

    <body>
        <div class="ui container messages">
            <div class="ui green info message">
                <div class="header">Success</div>
                <p>The payment has been successfully completed.</p>
                <?php
                    echo renderPaymentDetails($payment);
                ?>
            </div>
            <a href="<?php echo $_SESSION['startUrl']; ?>">go back</a>
        </div>
    </body>

</html>
