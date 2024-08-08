@extends('layouts.app')

@section('content')
    <div class="flex flex-col sm:flex-row gap-4">
        <!-- Column 1 -->
        <div class="flex-1 rounded-sm bg-white dark:bg-gray-800 dark:text-gray-300 p-4">
            <strong class="mb-4 block dark:text-gray-300">Hints:</strong>
            <ol>
                <p class="mb-4 dark:text-gray-300">
                    <strong class="dark:text-gray-100">1.</strong> We are a garments supplier in Bangladesh, manufacturing a
                    wide range of clothing for
                    men,
                    women, and children based on the samples and tech files provided by our customers. We do not keep any
                    pre-made designs or stock available.
                </p>
                <p class="mb-4 dark:text-gray-300">
                    <strong class="dark:text-gray-100">2.</strong> In order to provide you with accurate pricing for the
                    styles you desire, kindly
                    share
                    the
                    quantity, colors, sizes, packaging requirements, and also provide reference pictures for the desired
                    styling.
                </p>
                <p class="mb-4 dark:text-gray-300">
                    <strong class="dark:text-gray-100">3.</strong> Our general Minimum Order Quantity (MOQ) requirement for
                    adult-size garments,
                    covering four sizes and
                    three colors, is 3000 pieces per fabric type. However, the actual quantity may vary depending on the
                    complexity of the style and fabric construction.
                </p>
                <p class="mb-4 dark:text-gray-300"><strong class="dark:text-gray-100">4.</strong> Our payment terms involve a
                    30% advance payment upon order placement,
                    another 40% when the
                    production sample is approved, and the remaining amount prior to shipment dispatch. We require wire
                    transfer
                    bank to bank for payment, so kindly confirm that this method is acceptable. For larger amounts exceeding
                    100,000 USD, we prefer a transferable at sight payable letter of credit.</p>
            </ol>
        </div>

        <!-- Column 2 -->
        <div class="flex-1 rounded-sm bg-white p-4 dark:bg-gray-800 dark:text-gray-300">
            <strong class="mb-4 block dark:text-gray-300">Hints:</strong>
            <p class="mb-4 dark:text-gray-300">
                <strong class="dark:text-gray-100">1.</strong> We are a garments supplier in Bangladesh, manufacturing all
                sorts of clothing for men,
                women, and
                children based on customers' provided samples or tech files. We do not keep any stock or offer pre-made
                designs.
            </p>

            <p class="mb-4 dark:text-gray-300">
                <strong class="dark:text-gray-100">2.</strong> To provide you with accurate pricing for the styles you
                desire, please share the
                following details:
            </p>

            <ol class="pl-6 mb-4">
                <li class=" dark:text-gray-300">
                    a. Fabric details, including fabric type, composition, and construction.
                </li>
                <li class=" dark:text-gray-300">
                    b. Color list.
                </li>
                <li class=" dark:text-gray-300">
                    c. Size list and measurement chart in centimeters (CM).
                </li>
                <li class=" dark:text-gray-300">
                    d. Packing requirements for each style, along with a styling reference picture.
                </li>
            </ol>

            <p class="mb-4 dark:text-gray-300">
                <strong class="dark:text-gray-100">3.</strong> Our general Minimum Order Quantity (MOQ) requirement for a
                fabric type is 2000 pieces
                for adult-size
                garments, covering four sizes and two colors. The specific quantity may vary depending on the complexity of
                the style, fabric construction, and composition.
            </p>

            <p class="mb-4 dark:text-gray-300">
                <strong class="dark:text-gray-100">4.</strong> Our payment terms include a 30% advance payment upon order
                placement, another 40% when
                the production
                sample is approved, and the remaining amount prior to shipment dispatch. We require wire transfer bank to
                bank for payments. Please confirm if this payment method is acceptable. For larger amounts exceeding 100,000
                USD, we prefer a transferable at sight payable letter of credit.
            </p>
        </div>
    </div>
@endsection
