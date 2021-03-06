@extends('layouts.app1')

@section('content')
<?php //dd($products);?>
<style>
#collapseTwo{
   height: :350px !important;
   display:block !important;
}
</style>
@if(session()->has('msg'))
    <div class="alert alert-success" style="text-align:center">
        {{ session()->get('msg') }}
    </div>
@endif
<section class="checkout-page section-padding">
         <div class="container">
           <a href="{{url('/products')}}" class="btn btn-success"><i class="fa fa-chevron-left">&nbsp;&nbsp;</i>Home</a>
          <br>
          <br>
            <div class="row">
               <div class="col-md-8">
                  <div class="checkout-step">
                     <div class="accordion" id="accordionExample">
                        <div class="card checkout-step-one" style="display:none;">
                           <div class="card-header" id="headingOne">
                              <h5 class="mb-0">
                                 <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                 <span class="number">1</span> Phone Number Verification
                                 </button>
                              </h5>
                           </div>
                           <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                              <div class="card-body">
                                 <p>We need your phone number so that we can update you about your order.</p>
                                 <form>
                                    <div class="form-row align-items-center">
                                       <div class="col-auto">
                                          <label class="sr-only">phone number</label>
                                          <div class="input-group mb-2">
                                             <div class="input-group-prepend">
                                                <div class="input-group-text"><span class="mdi mdi-cellphone-iphone"></span></div>
                                             </div>
                                             <input type="text" class="form-control" placeholder="Enter phone number">
                                          </div>
                                       </div>
                                       <div class="col-auto">
                                          <button type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo" class="btn btn-secondary mb-2 btn-lg">NEXT</button>
                                       </div>
                                    </div>
                                 </form>
                              </div>
                           </div>
                        </div>
                        <div class="card checkout-step-two">
                           <div class="card-header" id="headingTwo azwarhere">
                              <h5 class="mb-0">
                                 <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                 <span class="number">1</span> Delivery Address
                                 </button>
                              </h5>
                           </div>
                           <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                              <div class="card-body">
                                 <form method="post" action="{{url('/checkout')}}">
                                    {{csrf_field()}}
                                    <div class="row">
                                       <div class="col-sm-6">
                                          <div class="form-group">
                                             <label class="control-label">First Name <span class="required">*</span></label>
                                             <input class="form-control border-form-control" name="ufname" value="" placeholder="" type="text" required>
                                             <input type="hidden" name="ordertotal" value="{{$ordertotal}}" />
                                          </div>
                                       </div>
                                       <div class="col-sm-6">
                                          <div class="form-group">
                                             <label class="control-label">Last Name <span class="required">*</span></label>
                                             <input class="form-control border-form-control"  name="ulname" value="" placeholder="" type="text" required>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="row">
                                       <div class="col-sm-6">
                                          <div class="form-group">
                                             <label class="control-label">Phone <span class="required">*</span></label>
                                             <input class="form-control border-form-control"  name="uphone" value="" placeholder="" type="text" required>
                                          </div>
                                       </div>
                                       <div class="col-sm-6">
                                          <div class="form-group">
                                             <label class="control-label">Email Address <span class="required">*</span></label>
                                             <input class="form-control border-form-control "  name="uemail" value="" placeholder=""  type="text" required>
                                          </div>
                                       </div>
                                    </div>
                                     <div class="row">
                                     <div class="col-sm-12">
                                       <div class="form-group">
                                          <label class="control-label">City <span class="required">*</span></label>
                                          <select class="select2 form-control border-form-control" data-select2-id="16" tabindex="0" aria-hidden="false" name="ucity">
                                             <option value="" data-select2-id="18">Select City</option>
                                             <option value="AF" data-select2-id="Noida">Noida</option>
                                             <option value="AX" data-select2-id="Greater Noida">Greater Noida</option>
                                             </select>
                                     </div>
                                    </div>
                                 </div>
                                    <div class="row">
                                       <!-- <div class="col-sm-6">
                                          <div class="form-group">
                                             <label class="control-label">Country <span class="required">*</span></label>
                                             <select class="select2 form-control border-form-control" data-select2-id="7" tabindex="0" aria-hidden="false" name="ucountry">
                                                <option value="" data-select2-id="9">Select Country</option>
                                                <option value="AF" data-select2-id="25">Afghanistan</option>
                                                <option value="AX" data-select2-id="26">??land Islands</option>
                                                <option value="AL" data-select2-id="27">Albania</option>
                                                <option value="DZ" data-select2-id="28">Algeria</option>
                                                <option value="AS" data-select2-id="29">American Samoa</option>
                                                <option value="AD" data-select2-id="30">Andorra</option>
                                                <option value="AO" data-select2-id="31">Angola</option>
                                                <option value="AI" data-select2-id="32">Anguilla</option>
                                                <option value="AQ" data-select2-id="33">Antarctica</option>
                                                <option value="AG" data-select2-id="34">Antigua and Barbuda</option>
                                                <option value="AR" data-select2-id="35">Argentina</option>
                                                <option value="AM" data-select2-id="36">Armenia</option>
                                                <option value="AW" data-select2-id="37">Aruba</option>
                                                <option value="AU" data-select2-id="38">Australia</option>
                                                <option value="AT" data-select2-id="39">Austria</option>
                                                <option value="AZ" data-select2-id="40">Azerbaijan</option>
                                                <option value="BS" data-select2-id="41">Bahamas</option>
                                                <option value="BH" data-select2-id="42">Bahrain</option>
                                                <option value="BD" data-select2-id="43">Bangladesh</option>
                                                <option value="BB" data-select2-id="44">Barbados</option>
                                                <option value="BY" data-select2-id="45">Belarus</option>
                                                <option value="BE" data-select2-id="46">Belgium</option>
                                                <option value="BZ" data-select2-id="47">Belize</option>
                                                <option value="BJ" data-select2-id="48">Benin</option>
                                                <option value="BM" data-select2-id="49">Bermuda</option>
                                                <option value="BT" data-select2-id="50">Bhutan</option>
                                                <option value="BO" data-select2-id="51">Bolivia, Plurinational State of</option>
                                                <option value="BQ" data-select2-id="52">Bonaire, Sint Eustatius and Saba</option>
                                                <option value="BA" data-select2-id="53">Bosnia and Herzegovina</option>
                                                <option value="BW" data-select2-id="54">Botswana</option>
                                                <option value="BV" data-select2-id="55">Bouvet Island</option>
                                                <option value="BR" data-select2-id="56">Brazil</option>
                                                <option value="IO" data-select2-id="57">British Indian Ocean Territory</option>
                                                <option value="BN" data-select2-id="58">Brunei Darussalam</option>
                                                <option value="BG" data-select2-id="59">Bulgaria</option>
                                                <option value="BF" data-select2-id="60">Burkina Faso</option>
                                                <option value="BI" data-select2-id="61">Burundi</option>
                                                <option value="KH" data-select2-id="62">Cambodia</option>
                                                <option value="CM" data-select2-id="63">Cameroon</option>
                                                <option value="CA" data-select2-id="64">Canada</option>
                                                <option value="CV" data-select2-id="65">Cape Verde</option>
                                                <option value="KY" data-select2-id="66">Cayman Islands</option>
                                                <option value="CF" data-select2-id="67">Central African Republic</option>
                                                <option value="TD" data-select2-id="68">Chad</option>
                                                <option value="CL" data-select2-id="69">Chile</option>
                                                <option value="CN" data-select2-id="70">China</option>
                                                <option value="CX" data-select2-id="71">Christmas Island</option>
                                                <option value="CC" data-select2-id="72">Cocos (Keeling) Islands</option>
                                                <option value="CO" data-select2-id="73">Colombia</option>
                                                <option value="KM" data-select2-id="74">Comoros</option>
                                                <option value="CG" data-select2-id="75">Congo</option>
                                                <option value="CD" data-select2-id="76">Congo, the Democratic Republic of the</option>
                                                <option value="CK" data-select2-id="77">Cook Islands</option>
                                                <option value="CR" data-select2-id="78">Costa Rica</option>
                                                <option value="CI" data-select2-id="79">C??te d'Ivoire</option>
                                                <option value="HR" data-select2-id="80">Croatia</option>
                                                <option value="CU" data-select2-id="81">Cuba</option>
                                                <option value="CW" data-select2-id="82">Cura??ao</option>
                                                <option value="CY" data-select2-id="83">Cyprus</option>
                                                <option value="CZ" data-select2-id="84">Czech Republic</option>
                                                <option value="DK" data-select2-id="85">Denmark</option>
                                                <option value="DJ" data-select2-id="86">Djibouti</option>
                                                <option value="DM" data-select2-id="87">Dominica</option>
                                                <option value="DO" data-select2-id="88">Dominican Republic</option>
                                                <option value="EC" data-select2-id="89">Ecuador</option>
                                                <option value="EG" data-select2-id="90">Egypt</option>
                                                <option value="SV" data-select2-id="91">El Salvador</option>
                                                <option value="GQ" data-select2-id="92">Equatorial Guinea</option>
                                                <option value="ER" data-select2-id="93">Eritrea</option>
                                                <option value="EE" data-select2-id="94">Estonia</option>
                                                <option value="ET" data-select2-id="95">Ethiopia</option>
                                                <option value="FK" data-select2-id="96">Falkland Islands (Malvinas)</option>
                                                <option value="FO" data-select2-id="97">Faroe Islands</option>
                                                <option value="FJ" data-select2-id="98">Fiji</option>
                                                <option value="FI" data-select2-id="99">Finland</option>
                                                <option value="FR" data-select2-id="100">France</option>
                                                <option value="GF" data-select2-id="101">French Guiana</option>
                                                <option value="PF" data-select2-id="102">French Polynesia</option>
                                                <option value="TF" data-select2-id="103">French Southern Territories</option>
                                                <option value="GA" data-select2-id="104">Gabon</option>
                                                <option value="GM" data-select2-id="105">Gambia</option>
                                                <option value="GE" data-select2-id="106">Georgia</option>
                                                <option value="DE" data-select2-id="107">Germany</option>
                                                <option value="GH" data-select2-id="108">Ghana</option>
                                                <option value="GI" data-select2-id="109">Gibraltar</option>
                                                <option value="GR" data-select2-id="110">Greece</option>
                                                <option value="GL" data-select2-id="111">Greenland</option>
                                                <option value="GD" data-select2-id="112">Grenada</option>
                                                <option value="GP" data-select2-id="113">Guadeloupe</option>
                                                <option value="GU" data-select2-id="114">Guam</option>
                                                <option value="GT" data-select2-id="115">Guatemala</option>
                                                <option value="GG" data-select2-id="116">Guernsey</option>
                                                <option value="GN" data-select2-id="117">Guinea</option>
                                                <option value="GW" data-select2-id="118">Guinea-Bissau</option>
                                                <option value="GY" data-select2-id="119">Guyana</option>
                                                <option value="HT" data-select2-id="120">Haiti</option>
                                                <option value="HM" data-select2-id="121">Heard Island and McDonald Islands</option>
                                                <option value="VA" data-select2-id="122">Holy See (Vatican City State)</option>
                                                <option value="HN" data-select2-id="123">Honduras</option>
                                                <option value="HK" data-select2-id="124">Hong Kong</option>
                                                <option value="HU" data-select2-id="125">Hungary</option>
                                                <option value="IS" data-select2-id="126">Iceland</option>
                                                <option value="IN" data-select2-id="127">India</option>
                                                <option value="ID" data-select2-id="128">Indonesia</option>
                                                <option value="IR" data-select2-id="129">Iran, Islamic Republic of</option>
                                                <option value="IQ" data-select2-id="130">Iraq</option>
                                                <option value="IE" data-select2-id="131">Ireland</option>
                                                <option value="IM" data-select2-id="132">Isle of Man</option>
                                                <option value="IL" data-select2-id="133">Israel</option>
                                                <option value="IT" data-select2-id="134">Italy</option>
                                                <option value="JM" data-select2-id="135">Jamaica</option>
                                                <option value="JP" data-select2-id="136">Japan</option>
                                                <option value="JE" data-select2-id="137">Jersey</option>
                                                <option value="JO" data-select2-id="138">Jordan</option>
                                                <option value="KZ" data-select2-id="139">Kazakhstan</option>
                                                <option value="KE" data-select2-id="140">Kenya</option>
                                                <option value="KI" data-select2-id="141">Kiribati</option>
                                                <option value="KP" data-select2-id="142">Korea, Democratic People's Republic of</option>
                                                <option value="KR" data-select2-id="143">Korea, Republic of</option>
                                                <option value="KW" data-select2-id="144">Kuwait</option>
                                                <option value="KG" data-select2-id="145">Kyrgyzstan</option>
                                                <option value="LA" data-select2-id="146">Lao People's Democratic Republic</option>
                                                <option value="LV" data-select2-id="147">Latvia</option>
                                                <option value="LB" data-select2-id="148">Lebanon</option>
                                                <option value="LS" data-select2-id="149">Lesotho</option>
                                                <option value="LR" data-select2-id="150">Liberia</option>
                                                <option value="LY" data-select2-id="151">Libya</option>
                                                <option value="LI" data-select2-id="152">Liechtenstein</option>
                                                <option value="LT" data-select2-id="153">Lithuania</option>
                                                <option value="LU" data-select2-id="154">Luxembourg</option>
                                                <option value="MO" data-select2-id="155">Macao</option>
                                                <option value="MK" data-select2-id="156">Macedonia, the former Yugoslav Republic of</option>
                                                <option value="MG" data-select2-id="157">Madagascar</option>
                                                <option value="MW" data-select2-id="158">Malawi</option>
                                                <option value="MY" data-select2-id="159">Malaysia</option>
                                                <option value="MV" data-select2-id="160">Maldives</option>
                                                <option value="ML" data-select2-id="161">Mali</option>
                                                <option value="MT" data-select2-id="162">Malta</option>
                                                <option value="MH" data-select2-id="163">Marshall Islands</option>
                                                <option value="MQ" data-select2-id="164">Martinique</option>
                                                <option value="MR" data-select2-id="165">Mauritania</option>
                                                <option value="MU" data-select2-id="166">Mauritius</option>
                                                <option value="YT" data-select2-id="167">Mayotte</option>
                                                <option value="MX" data-select2-id="168">Mexico</option>
                                                <option value="FM" data-select2-id="169">Micronesia, Federated States of</option>
                                                <option value="MD" data-select2-id="170">Moldova, Republic of</option>
                                                <option value="MC" data-select2-id="171">Monaco</option>
                                                <option value="MN" data-select2-id="172">Mongolia</option>
                                                <option value="ME" data-select2-id="173">Montenegro</option>
                                                <option value="MS" data-select2-id="174">Montserrat</option>
                                                <option value="MA" data-select2-id="175">Morocco</option>
                                                <option value="MZ" data-select2-id="176">Mozambique</option>
                                                <option value="MM" data-select2-id="177">Myanmar</option>
                                                <option value="NA" data-select2-id="178">Namibia</option>
                                                <option value="NR" data-select2-id="179">Nauru</option>
                                                <option value="NP" data-select2-id="180">Nepal</option>
                                                <option value="NL" data-select2-id="181">Netherlands</option>
                                                <option value="NC" data-select2-id="182">New Caledonia</option>
                                                <option value="NZ" data-select2-id="183">New Zealand</option>
                                                <option value="NI" data-select2-id="184">Nicaragua</option>
                                                <option value="NE" data-select2-id="185">Niger</option>
                                                <option value="NG" data-select2-id="186">Nigeria</option>
                                                <option value="NU" data-select2-id="187">Niue</option>
                                                <option value="NF" data-select2-id="188">Norfolk Island</option>
                                                <option value="MP" data-select2-id="189">Northern Mariana Islands</option>
                                                <option value="NO" data-select2-id="190">Norway</option>
                                                <option value="OM" data-select2-id="191">Oman</option>
                                                <option value="PK" data-select2-id="192">Pakistan</option>
                                                <option value="PW" data-select2-id="193">Palau</option>
                                                <option value="PS" data-select2-id="194">Palestinian Territory, Occupied</option>
                                                <option value="PA" data-select2-id="195">Panama</option>
                                                <option value="PG" data-select2-id="196">Papua New Guinea</option>
                                                <option value="PY" data-select2-id="197">Paraguay</option>
                                                <option value="PE" data-select2-id="198">Peru</option>
                                                <option value="PH" data-select2-id="199">Philippines</option>
                                                <option value="PN" data-select2-id="200">Pitcairn</option>
                                                <option value="PL" data-select2-id="201">Poland</option>
                                                <option value="PT" data-select2-id="202">Portugal</option>
                                                <option value="PR" data-select2-id="203">Puerto Rico</option>
                                                <option value="QA" data-select2-id="204">Qatar</option>
                                                <option value="RE" data-select2-id="205">R??union</option>
                                                <option value="RO" data-select2-id="206">Romania</option>
                                                <option value="RU" data-select2-id="207">Russian Federation</option>
                                                <option value="RW" data-select2-id="208">Rwanda</option>
                                                <option value="BL" data-select2-id="209">Saint Barth??lemy</option>
                                                <option value="SH" data-select2-id="210">Saint Helena, Ascension and Tristan da Cunha</option>
                                                <option value="KN" data-select2-id="211">Saint Kitts and Nevis</option>
                                                <option value="LC" data-select2-id="212">Saint Lucia</option>
                                                <option value="MF" data-select2-id="213">Saint Martin (French part)</option>
                                                <option value="PM" data-select2-id="214">Saint Pierre and Miquelon</option>
                                                <option value="VC" data-select2-id="215">Saint Vincent and the Grenadines</option>
                                                <option value="WS" data-select2-id="216">Samoa</option>
                                                <option value="SM" data-select2-id="217">San Marino</option>
                                                <option value="ST" data-select2-id="218">Sao Tome and Principe</option>
                                                <option value="SA" data-select2-id="219">Saudi Arabia</option>
                                                <option value="SN" data-select2-id="220">Senegal</option>
                                                <option value="RS" data-select2-id="221">Serbia</option>
                                                <option value="SC" data-select2-id="222">Seychelles</option>
                                                <option value="SL" data-select2-id="223">Sierra Leone</option>
                                                <option value="SG" data-select2-id="224">Singapore</option>
                                                <option value="SX" data-select2-id="225">Sint Maarten (Dutch part)</option>
                                                <option value="SK" data-select2-id="226">Slovakia</option>
                                                <option value="SI" data-select2-id="227">Slovenia</option>
                                                <option value="SB" data-select2-id="228">Solomon Islands</option>
                                                <option value="SO" data-select2-id="229">Somalia</option>
                                                <option value="ZA" data-select2-id="230">South Africa</option>
                                                <option value="GS" data-select2-id="231">South Georgia and the South Sandwich Islands</option>
                                                <option value="SS" data-select2-id="232">South Sudan</option>
                                                <option value="ES" data-select2-id="233">Spain</option>
                                                <option value="LK" data-select2-id="234">Sri Lanka</option>
                                                <option value="SD" data-select2-id="235">Sudan</option>
                                                <option value="SR" data-select2-id="236">Suriname</option>
                                                <option value="SJ" data-select2-id="237">Svalbard and Jan Mayen</option>
                                                <option value="SZ" data-select2-id="238">Swaziland</option>
                                                <option value="SE" data-select2-id="239">Sweden</option>
                                                <option value="CH" data-select2-id="240">Switzerland</option>
                                                <option value="SY" data-select2-id="241">Syrian Arab Republic</option>
                                                <option value="TW" data-select2-id="242">Taiwan, Province of China</option>
                                                <option value="TJ" data-select2-id="243">Tajikistan</option>
                                                <option value="TZ" data-select2-id="244">Tanzania, United Republic of</option>
                                                <option value="TH" data-select2-id="245">Thailand</option>
                                                <option value="TL" data-select2-id="246">Timor-Leste</option>
                                                <option value="TG" data-select2-id="247">Togo</option>
                                                <option value="TK" data-select2-id="248">Tokelau</option>
                                                <option value="TO" data-select2-id="249">Tonga</option>
                                                <option value="TT" data-select2-id="250">Trinidad and Tobago</option>
                                                <option value="TN" data-select2-id="251">Tunisia</option>
                                                <option value="TR" data-select2-id="252">Turkey</option>
                                                <option value="TM" data-select2-id="253">Turkmenistan</option>
                                                <option value="TC" data-select2-id="254">Turks and Caicos Islands</option>
                                                <option value="TV" data-select2-id="255">Tuvalu</option>
                                                <option value="UG" data-select2-id="256">Uganda</option>
                                                <option value="UA" data-select2-id="257">Ukraine</option>
                                                <option value="AE" data-select2-id="258">United Arab Emirates</option>
                                                <option value="GB" data-select2-id="259">United Kingdom</option>
                                                <option value="US" data-select2-id="260">United States</option>
                                                <option value="UM" data-select2-id="261">United States Minor Outlying Islands</option>
                                                <option value="UY" data-select2-id="262">Uruguay</option>
                                                <option value="UZ" data-select2-id="263">Uzbekistan</option>
                                                <option value="VU" data-select2-id="264">Vanuatu</option>
                                                <option value="VE" data-select2-id="265">Venezuela, Bolivarian Republic of</option>
                                                <option value="VN" data-select2-id="266">Viet Nam</option>
                                                <option value="VG" data-select2-id="267">Virgin Islands, British</option>
                                                <option value="VI" data-select2-id="268">Virgin Islands, U.S.</option>
                                                <option value="WF" data-select2-id="269">Wallis and Futuna</option>
                                                <option value="EH" data-select2-id="270">Western Sahara</option>
                                                <option value="YE" data-select2-id="271">Yemen</option>
                                                <option value="ZM" data-select2-id="272">Zambia</option>
                                                <option value="ZW" data-select2-id="273">Zimbabwe</option>
                                             </select>
                                          </div>
                                       </div> -->
                                       <div class="col-sm-12" style="display:none;">
                                          <div class="form-group">
                                             <label class="control-label">City <span class="required">*</span></label>
                                             <select class="select2 form-control border-form-control" data-select2-id="10" tabindex="0" aria-hidden="false" name="ucity">
                                                <option value="" data-select2-id="12">Select City</option>
                                                <option value="AF" data-select2-id="Noida">Noida</option>
                                                <option value="AX" data-select2-id="New Delhi">New Delhi</option>
                                                <option value="AL" data-select2-id="Gaziabaaad">Gaziabaaad</option>
                                                <option value="DZ" data-select2-id="Allahabad">Allahabad</option>
                                             </select>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="row" style="display:none;">
                                       <div class="col-sm-6">
                                          <div class="form-group">
                                             <label class="control-label">Zip Code <span class="required">*</span></label>
                                             <input class="form-control border-form-control"  name="uzip" value="" placeholder="123456" type="number">
                                          </div>
                                       </div>
                                       <div class="col-sm-6">
                                          <div class="form-group">
                                             <label class="control-label">State <span class="required">*</span></label>
                                             <select class="select2 form-control border-form-control" data-select2-id="13" tabindex="0" aria-hidden="false" name="ustate">
                                                <option value="" data-select2-id="15">Select State</option>
                                                <option value="AF" data-select2-id="UP">Uttra pradesh</option>
                                                <option value="AX" data-select2-id="New Delhi">New Delhi</option>
                                                <option value="AL" data-select2-id="Madhya pradesh">Madhya pradesh</option>
                                                <option value="DZ" data-select2-id="orissa">orissa</option>
                                             </select>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="row">
                                       <div class="col-sm-12">
                                          <div class="form-group">
                                             <label class="control-label">Shipping Address <span class="required">*</span></label>
                                             <textarea class="form-control border-form-control" name="ushippaddr" required></textarea>
                                             <small class="text-danger">Please provide the number and street.</small>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="row">
                                       <div class="col-sm-12">
                                          <div class="form-group">
                                             <div class="custom-control custom-checkbox">
                                                <input type="checkbox" required class="custom-control-input" id="" style="z-index: 2;">
                                                <label class="custom-control-label" for="">I accept the  <a href="{{url('/terms')}}" style="color: #e96125; cursor: pointer;">terms and conditions.</a></label>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                       <!--added-->
                                    <div class="instruction row">
                                       <div class="col-md-2"><b>Note:</b></div>
                                       <div class="col-md-10">
                                          <p style="color:red;">Prices are subject to change depending on the market.You can pay through cash, through Paytm ( 7827087448 ).</p>
                                        <p style="color:red;">For Easy Ordering: You can also  Whatsapp to us.     8588037272
                                       </p>
                                       <p  style="color:red;">1. Bulk orders must  be given in one-day advance   call @ 8588037272</p>
                                       <p  style="color:red;">2. PLEASE CHECK THE QUALITY AT THE TIME OF DELIVERY. AFTER THAT NO REPLACEMENT WILL CONSIDER.*</p>
                                       <p style="color:red">3. OFFER IS NOT VALID DAIRY PRODUCT AND NON VEG</p>
                                       <p style="color:red">4. Kindly order by 11 PM for the next day morning from 9.30am to 11 am fresh home delivery</p>
                                       <p style="color:red">5. For non- veg items, no replacement will be done.</p>
                                       </div>
                                 </div>
                                       <!--added-->

                                    </div>
                                    <!-- <div class="heading-part">
                                       <h3 class="sub-heading">Billing Address</h3>
                                    </div> -->
                                    <hr>
                                    <!-- <div class="row">
                                       <div class="col-sm-6">
                                          <div class="form-group">
                                             <label class="control-label">First Name <span class="required">*</span></label>
                                             <input class="form-control border-form-control"  name="ubfname" value="" placeholder="Gurdeep" type="text">
                                          </div>
                                       </div>
                                       <div class="col-sm-6">
                                          <div class="form-group">
                                             <label class="control-label">Last Name <span class="required">*</span></label>
                                             <input class="form-control border-form-control"  name="ublname" value="" placeholder="Osahan" type="text">
                                          </div>
                                       </div>
                                    </div> -->
                                   <!--  <div class="row">
                                       <div class="col-sm-6">
                                          <div class="form-group">
                                             <label class="control-label">Phone <span class="required">*</span></label>
                                             <input class="form-control border-form-control" name="ubphone"  value="" placeholder="123 456 7890" type="number">
                                          </div>
                                       </div>
                                       <div class="col-sm-6">
                                          <div class="form-group">
                                             <label class="control-label">Email Address <span class="required">*</span></label>
                                             <input class="form-control border-form-control " value="" placeholder="dailyneeds@gmail.com" disabled="" type="email" name="ubemail">
                                          </div>
                                       </div>
                                    </div> -->
                                   <!--  <div class="row">
                                       <div class="col-sm-6">
                                          <div class="form-group">
                                             <label class="control-label">Country <span class="required">*</span></label>
                                             <select class="select2 form-control border-form-control" data-select2-id="16" tabindex="0" aria-hidden="false" name="ubcountry">
                                                <option value="" data-select2-id="18">Select Country</option>
                                                <option value="AF" data-select2-id="285">Afghanistan</option>
                                                <option value="AX" data-select2-id="286">??land Islands</option>
                                                <option value="AL" data-select2-id="287">Albania</option>
                                                <option value="DZ" data-select2-id="288">Algeria</option>
                                                <option value="AS" data-select2-id="289">American Samoa</option>
                                                <option value="AD" data-select2-id="290">Andorra</option>
                                                <option value="AO" data-select2-id="291">Angola</option>
                                                <option value="AI" data-select2-id="292">Anguilla</option>
                                                <option value="AQ" data-select2-id="293">Antarctica</option>
                                                <option value="AG" data-select2-id="294">Antigua and Barbuda</option>
                                                <option value="AR" data-select2-id="295">Argentina</option>
                                                <option value="AM" data-select2-id="296">Armenia</option>
                                                <option value="AW" data-select2-id="297">Aruba</option>
                                                <option value="AU" data-select2-id="298">Australia</option>
                                                <option value="AT" data-select2-id="299">Austria</option>
                                                <option value="AZ" data-select2-id="300">Azerbaijan</option>
                                                <option value="BS" data-select2-id="301">Bahamas</option>
                                                <option value="BH" data-select2-id="302">Bahrain</option>
                                                <option value="BD" data-select2-id="303">Bangladesh</option>
                                                <option value="BB" data-select2-id="304">Barbados</option>
                                                <option value="BY" data-select2-id="305">Belarus</option>
                                                <option value="BE" data-select2-id="306">Belgium</option>
                                                <option value="BZ" data-select2-id="307">Belize</option>
                                                <option value="BJ" data-select2-id="308">Benin</option>
                                                <option value="BM" data-select2-id="309">Bermuda</option>
                                                <option value="BT" data-select2-id="310">Bhutan</option>
                                                <option value="BO" data-select2-id="311">Bolivia, Plurinational State of</option>
                                                <option value="BQ" data-select2-id="312">Bonaire, Sint Eustatius and Saba</option>
                                                <option value="BA" data-select2-id="313">Bosnia and Herzegovina</option>
                                                <option value="BW" data-select2-id="314">Botswana</option>
                                                <option value="BV" data-select2-id="315">Bouvet Island</option>
                                                <option value="BR" data-select2-id="316">Brazil</option>
                                                <option value="IO" data-select2-id="317">British Indian Ocean Territory</option>
                                                <option value="BN" data-select2-id="318">Brunei Darussalam</option>
                                                <option value="BG" data-select2-id="319">Bulgaria</option>
                                                <option value="BF" data-select2-id="320">Burkina Faso</option>
                                                <option value="BI" data-select2-id="321">Burundi</option>
                                                <option value="KH" data-select2-id="322">Cambodia</option>
                                                <option value="CM" data-select2-id="323">Cameroon</option>
                                                <option value="CA" data-select2-id="324">Canada</option>
                                                <option value="CV" data-select2-id="325">Cape Verde</option>
                                                <option value="KY" data-select2-id="326">Cayman Islands</option>
                                                <option value="CF" data-select2-id="327">Central African Republic</option>
                                                <option value="TD" data-select2-id="328">Chad</option>
                                                <option value="CL" data-select2-id="329">Chile</option>
                                                <option value="CN" data-select2-id="330">China</option>
                                                <option value="CX" data-select2-id="331">Christmas Island</option>
                                                <option value="CC" data-select2-id="332">Cocos (Keeling) Islands</option>
                                                <option value="CO" data-select2-id="333">Colombia</option>
                                                <option value="KM" data-select2-id="334">Comoros</option>
                                                <option value="CG" data-select2-id="335">Congo</option>
                                                <option value="CD" data-select2-id="336">Congo, the Democratic Republic of the</option>
                                                <option value="CK" data-select2-id="337">Cook Islands</option>
                                                <option value="CR" data-select2-id="338">Costa Rica</option>
                                                <option value="CI" data-select2-id="339">C??te d'Ivoire</option>
                                                <option value="HR" data-select2-id="340">Croatia</option>
                                                <option value="CU" data-select2-id="341">Cuba</option>
                                                <option value="CW" data-select2-id="342">Cura??ao</option>
                                                <option value="CY" data-select2-id="343">Cyprus</option>
                                                <option value="CZ" data-select2-id="344">Czech Republic</option>
                                                <option value="DK" data-select2-id="345">Denmark</option>
                                                <option value="DJ" data-select2-id="346">Djibouti</option>
                                                <option value="DM" data-select2-id="347">Dominica</option>
                                                <option value="DO" data-select2-id="348">Dominican Republic</option>
                                                <option value="EC" data-select2-id="349">Ecuador</option>
                                                <option value="EG" data-select2-id="350">Egypt</option>
                                                <option value="SV" data-select2-id="351">El Salvador</option>
                                                <option value="GQ" data-select2-id="352">Equatorial Guinea</option>
                                                <option value="ER" data-select2-id="353">Eritrea</option>
                                                <option value="EE" data-select2-id="354">Estonia</option>
                                                <option value="ET" data-select2-id="355">Ethiopia</option>
                                                <option value="FK" data-select2-id="356">Falkland Islands (Malvinas)</option>
                                                <option value="FO" data-select2-id="357">Faroe Islands</option>
                                                <option value="FJ" data-select2-id="358">Fiji</option>
                                                <option value="FI" data-select2-id="359">Finland</option>
                                                <option value="FR" data-select2-id="360">France</option>
                                                <option value="GF" data-select2-id="361">French Guiana</option>
                                                <option value="PF" data-select2-id="362">French Polynesia</option>
                                                <option value="TF" data-select2-id="363">French Southern Territories</option>
                                                <option value="GA" data-select2-id="364">Gabon</option>
                                                <option value="GM" data-select2-id="365">Gambia</option>
                                                <option value="GE" data-select2-id="366">Georgia</option>
                                                <option value="DE" data-select2-id="367">Germany</option>
                                                <option value="GH" data-select2-id="368">Ghana</option>
                                                <option value="GI" data-select2-id="369">Gibraltar</option>
                                                <option value="GR" data-select2-id="370">Greece</option>
                                                <option value="GL" data-select2-id="371">Greenland</option>
                                                <option value="GD" data-select2-id="372">Grenada</option>
                                                <option value="GP" data-select2-id="373">Guadeloupe</option>
                                                <option value="GU" data-select2-id="374">Guam</option>
                                                <option value="GT" data-select2-id="375">Guatemala</option>
                                                <option value="GG" data-select2-id="376">Guernsey</option>
                                                <option value="GN" data-select2-id="377">Guinea</option>
                                                <option value="GW" data-select2-id="378">Guinea-Bissau</option>
                                                <option value="GY" data-select2-id="379">Guyana</option>
                                                <option value="HT" data-select2-id="380">Haiti</option>
                                                <option value="HM" data-select2-id="381">Heard Island and McDonald Islands</option>
                                                <option value="VA" data-select2-id="382">Holy See (Vatican City State)</option>
                                                <option value="HN" data-select2-id="383">Honduras</option>
                                                <option value="HK" data-select2-id="384">Hong Kong</option>
                                                <option value="HU" data-select2-id="385">Hungary</option>
                                                <option value="IS" data-select2-id="386">Iceland</option>
                                                <option value="IN" data-select2-id="387">India</option>
                                                <option value="ID" data-select2-id="388">Indonesia</option>
                                                <option value="IR" data-select2-id="389">Iran, Islamic Republic of</option>
                                                <option value="IQ" data-select2-id="390">Iraq</option>
                                                <option value="IE" data-select2-id="391">Ireland</option>
                                                <option value="IM" data-select2-id="392">Isle of Man</option>
                                                <option value="IL" data-select2-id="393">Israel</option>
                                                <option value="IT" data-select2-id="394">Italy</option>
                                                <option value="JM" data-select2-id="395">Jamaica</option>
                                                <option value="JP" data-select2-id="396">Japan</option>
                                                <option value="JE" data-select2-id="397">Jersey</option>
                                                <option value="JO" data-select2-id="398">Jordan</option>
                                                <option value="KZ" data-select2-id="399">Kazakhstan</option>
                                                <option value="KE" data-select2-id="400">Kenya</option>
                                                <option value="KI" data-select2-id="401">Kiribati</option>
                                                <option value="KP" data-select2-id="402">Korea, Democratic People's Republic of</option>
                                                <option value="KR" data-select2-id="403">Korea, Republic of</option>
                                                <option value="KW" data-select2-id="404">Kuwait</option>
                                                <option value="KG" data-select2-id="405">Kyrgyzstan</option>
                                                <option value="LA" data-select2-id="406">Lao People's Democratic Republic</option>
                                                <option value="LV" data-select2-id="407">Latvia</option>
                                                <option value="LB" data-select2-id="408">Lebanon</option>
                                                <option value="LS" data-select2-id="409">Lesotho</option>
                                                <option value="LR" data-select2-id="410">Liberia</option>
                                                <option value="LY" data-select2-id="411">Libya</option>
                                                <option value="LI" data-select2-id="412">Liechtenstein</option>
                                                <option value="LT" data-select2-id="413">Lithuania</option>
                                                <option value="LU" data-select2-id="414">Luxembourg</option>
                                                <option value="MO" data-select2-id="415">Macao</option>
                                                <option value="MK" data-select2-id="416">Macedonia, the former Yugoslav Republic of</option>
                                                <option value="MG" data-select2-id="417">Madagascar</option>
                                                <option value="MW" data-select2-id="418">Malawi</option>
                                                <option value="MY" data-select2-id="419">Malaysia</option>
                                                <option value="MV" data-select2-id="420">Maldives</option>
                                                <option value="ML" data-select2-id="421">Mali</option>
                                                <option value="MT" data-select2-id="422">Malta</option>
                                                <option value="MH" data-select2-id="423">Marshall Islands</option>
                                                <option value="MQ" data-select2-id="424">Martinique</option>
                                                <option value="MR" data-select2-id="425">Mauritania</option>
                                                <option value="MU" data-select2-id="426">Mauritius</option>
                                                <option value="YT" data-select2-id="427">Mayotte</option>
                                                <option value="MX" data-select2-id="428">Mexico</option>
                                                <option value="FM" data-select2-id="429">Micronesia, Federated States of</option>
                                                <option value="MD" data-select2-id="430">Moldova, Republic of</option>
                                                <option value="MC" data-select2-id="431">Monaco</option>
                                                <option value="MN" data-select2-id="432">Mongolia</option>
                                                <option value="ME" data-select2-id="433">Montenegro</option>
                                                <option value="MS" data-select2-id="434">Montserrat</option>
                                                <option value="MA" data-select2-id="435">Morocco</option>
                                                <option value="MZ" data-select2-id="436">Mozambique</option>
                                                <option value="MM" data-select2-id="437">Myanmar</option>
                                                <option value="NA" data-select2-id="438">Namibia</option>
                                                <option value="NR" data-select2-id="439">Nauru</option>
                                                <option value="NP" data-select2-id="440">Nepal</option>
                                                <option value="NL" data-select2-id="441">Netherlands</option>
                                                <option value="NC" data-select2-id="442">New Caledonia</option>
                                                <option value="NZ" data-select2-id="443">New Zealand</option>
                                                <option value="NI" data-select2-id="444">Nicaragua</option>
                                                <option value="NE" data-select2-id="445">Niger</option>
                                                <option value="NG" data-select2-id="446">Nigeria</option>
                                                <option value="NU" data-select2-id="447">Niue</option>
                                                <option value="NF" data-select2-id="448">Norfolk Island</option>
                                                <option value="MP" data-select2-id="449">Northern Mariana Islands</option>
                                                <option value="NO" data-select2-id="450">Norway</option>
                                                <option value="OM" data-select2-id="451">Oman</option>
                                                <option value="PK" data-select2-id="452">Pakistan</option>
                                                <option value="PW" data-select2-id="453">Palau</option>
                                                <option value="PS" data-select2-id="454">Palestinian Territory, Occupied</option>
                                                <option value="PA" data-select2-id="455">Panama</option>
                                                <option value="PG" data-select2-id="456">Papua New Guinea</option>
                                                <option value="PY" data-select2-id="457">Paraguay</option>
                                                <option value="PE" data-select2-id="458">Peru</option>
                                                <option value="PH" data-select2-id="459">Philippines</option>
                                                <option value="PN" data-select2-id="460">Pitcairn</option>
                                                <option value="PL" data-select2-id="461">Poland</option>
                                                <option value="PT" data-select2-id="462">Portugal</option>
                                                <option value="PR" data-select2-id="463">Puerto Rico</option>
                                                <option value="QA" data-select2-id="464">Qatar</option>
                                                <option value="RE" data-select2-id="465">R??union</option>
                                                <option value="RO" data-select2-id="466">Romania</option>
                                                <option value="RU" data-select2-id="467">Russian Federation</option>
                                                <option value="RW" data-select2-id="468">Rwanda</option>
                                                <option value="BL" data-select2-id="469">Saint Barth??lemy</option>
                                                <option value="SH" data-select2-id="470">Saint Helena, Ascension and Tristan da Cunha</option>
                                                <option value="KN" data-select2-id="471">Saint Kitts and Nevis</option>
                                                <option value="LC" data-select2-id="472">Saint Lucia</option>
                                                <option value="MF" data-select2-id="473">Saint Martin (French part)</option>
                                                <option value="PM" data-select2-id="474">Saint Pierre and Miquelon</option>
                                                <option value="VC" data-select2-id="475">Saint Vincent and the Grenadines</option>
                                                <option value="WS" data-select2-id="476">Samoa</option>
                                                <option value="SM" data-select2-id="477">San Marino</option>
                                                <option value="ST" data-select2-id="478">Sao Tome and Principe</option>
                                                <option value="SA" data-select2-id="479">Saudi Arabia</option>
                                                <option value="SN" data-select2-id="480">Senegal</option>
                                                <option value="RS" data-select2-id="481">Serbia</option>
                                                <option value="SC" data-select2-id="482">Seychelles</option>
                                                <option value="SL" data-select2-id="483">Sierra Leone</option>
                                                <option value="SG" data-select2-id="484">Singapore</option>
                                                <option value="SX" data-select2-id="485">Sint Maarten (Dutch part)</option>
                                                <option value="SK" data-select2-id="486">Slovakia</option>
                                                <option value="SI" data-select2-id="487">Slovenia</option>
                                                <option value="SB" data-select2-id="488">Solomon Islands</option>
                                                <option value="SO" data-select2-id="489">Somalia</option>
                                                <option value="ZA" data-select2-id="490">South Africa</option>
                                                <option value="GS" data-select2-id="491">South Georgia and the South Sandwich Islands</option>
                                                <option value="SS" data-select2-id="492">South Sudan</option>
                                                <option value="ES" data-select2-id="493">Spain</option>
                                                <option value="LK" data-select2-id="494">Sri Lanka</option>
                                                <option value="SD" data-select2-id="495">Sudan</option>
                                                <option value="SR" data-select2-id="496">Suriname</option>
                                                <option value="SJ" data-select2-id="497">Svalbard and Jan Mayen</option>
                                                <option value="SZ" data-select2-id="498">Swaziland</option>
                                                <option value="SE" data-select2-id="499">Sweden</option>
                                                <option value="CH" data-select2-id="500">Switzerland</option>
                                                <option value="SY" data-select2-id="501">Syrian Arab Republic</option>
                                                <option value="TW" data-select2-id="502">Taiwan, Province of China</option>
                                                <option value="TJ" data-select2-id="503">Tajikistan</option>
                                                <option value="TZ" data-select2-id="504">Tanzania, United Republic of</option>
                                                <option value="TH" data-select2-id="505">Thailand</option>
                                                <option value="TL" data-select2-id="506">Timor-Leste</option>
                                                <option value="TG" data-select2-id="507">Togo</option>
                                                <option value="TK" data-select2-id="508">Tokelau</option>
                                                <option value="TO" data-select2-id="509">Tonga</option>
                                                <option value="TT" data-select2-id="510">Trinidad and Tobago</option>
                                                <option value="TN" data-select2-id="511">Tunisia</option>
                                                <option value="TR" data-select2-id="512">Turkey</option>
                                                <option value="TM" data-select2-id="513">Turkmenistan</option>
                                                <option value="TC" data-select2-id="514">Turks and Caicos Islands</option>
                                                <option value="TV" data-select2-id="515">Tuvalu</option>
                                                <option value="UG" data-select2-id="516">Uganda</option>
                                                <option value="UA" data-select2-id="517">Ukraine</option>
                                                <option value="AE" data-select2-id="518">United Arab Emirates</option>
                                                <option value="GB" data-select2-id="519">United Kingdom</option>
                                                <option value="US" data-select2-id="520">United States</option>
                                                <option value="UM" data-select2-id="521">United States Minor Outlying Islands</option>
                                                <option value="UY" data-select2-id="522">Uruguay</option>
                                                <option value="UZ" data-select2-id="523">Uzbekistan</option>
                                                <option value="VU" data-select2-id="524">Vanuatu</option>
                                                <option value="VE" data-select2-id="525">Venezuela, Bolivarian Republic of</option>
                                                <option value="VN" data-select2-id="526">Viet Nam</option>
                                                <option value="VG" data-select2-id="527">Virgin Islands, British</option>
                                                <option value="VI" data-select2-id="528">Virgin Islands, U.S.</option>
                                                <option value="WF" data-select2-id="529">Wallis and Futuna</option>
                                                <option value="EH" data-select2-id="530">Western Sahara</option>
                                                <option value="YE" data-select2-id="531">Yemen</option>
                                                <option value="ZM" data-select2-id="532">Zambia</option>
                                                <option value="ZW" data-select2-id="533">Zimbabwe</option>
                                             </select>
                                          </div>
                                       </div> -->
                                       <!-- <div class="col-sm-6">
                                          <div class="form-group">
                                             <label class="control-label">City <span class="required">*</span></label>
                                             <select class="select2 form-control border-form-control" data-select2-id="19" tabindex="0" aria-hidden="false" name="ubcity">
                                                <option value="" data-select2-id="21">Select City</option>
                                                <option value="AF" data-select2-id="Alaska">Alaska</option>
                                                <option value="AX" data-select2-id="New Hampshire">New Hampshire</option>
                                                <option value="AL" data-select2-id="Oregon">Oregon</option>
                                                <option value="DZ" data-select2-id="Toronto">Toronto</option>
                                             </select>
                                          </div>
                                       </div> 
                                    </div>-->
                                   <!--  <div class="row">
                                       <div class="col-sm-6">
                                          <div class="form-group">
                                             <label class="control-label">Zip Code <span class="required">*</span></label>
                                             <input class="form-control border-form-control"  name="ubzip" value="" placeholder="123456" type="number">
                                          </div>
                                       </div>
                                       <div class="col-sm-6">
                                          <div class="form-group">
                                             <label class="control-label">State <span class="required">*</span></label>
                                             <select class="select2 form-control border-form-control" data-select2-id="22" tabindex="0" aria-hidden="false" name="ubstate">
                                                <option value="" data-select2-id="24">Select State</option>
                                                <option value="AF" data-select2-id="California">California</option>
                                                <option value="AX" data-select2-id="Florida">Florida</option>
                                                <option value="AL" data-select2-id="Georgia">Georgia</option>
                                                <option value="DZ" data-select2-id="Idaho">Idaho</option>
                                             </select>
                                          </div>
                                       </div>
                                    </div> -->
                                   <!--  <div class="row">
                                       <div class="col-sm-12">
                                          <div class="form-group">
                                             <label class="control-label">Billing Landmark <span class="required">*</span></label>
                                             <textarea class="form-control border-form-control"></textarea>
                                             <small class="text-danger">
                                             Please include landmark (e.g : Opposite Bank) as the carrier service may find it easier to locate your address.
                                             </small>
                                          </div>
                                       </div>
                                    </div> -->
                                    <!-- <div class="custom-control custom-checkbox mb-3">
                                       <input type="checkbox" class="custom-control-input" id="customCheckbill" name="landmark">
                                       <label class="custom-control-label" for="customCheckbill">Use my delivery address as my billing address</label>
                                    </div> -->
                                    <input type="submit" name="submit" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree" class="btn btn-secondary mb-2 btn-lg" value="NEXT" />
                                 </form>
                              </div>
                           </div>
                        </div>
                        <div class="card" style="display:none;">
                           <div class="card-header" id="headingThree">
                              <h5 class="mb-0">
                                 <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                 <span class="number">3</span> Payment
                                 </button>
                              </h5>
                           </div>
                           <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                              <div class="card-body">
                                 <form class="col-lg-8 col-md-8 mx-auto">
                                    <div class="form-group">
                                       <label class="control-label">Card Number</label>
                                       <input class="form-control border-form-control" value="" placeholder="0000 0000 0000 0000" type="text">
                                    </div>
                                    <div class="row">
                                       <div class="col-sm-3">
                                          <div class="form-group">
                                             <label class="control-label">Month</label>
                                             <input class="form-control border-form-control" value="" placeholder="01" type="text">
                                          </div>
                                       </div>
                                       <div class="col-sm-3">
                                          <div class="form-group">
                                             <label class="control-label">Year</label>
                                             <input class="form-control border-form-control" value="" placeholder="15" type="text">
                                          </div>
                                       </div>
                                       <div class="col-sm-3">
                                       </div>
                                       <div class="col-sm-3">
                                          <div class="form-group">
                                             <label class="control-label">CVV</label>
                                             <input class="form-control border-form-control" value="" placeholder="135" type="text">
                                          </div>
                                       </div>
                                    </div>
                                    <hr>
                                    <div class="custom-control custom-radio">
                                       <input type="radio" id="customRadio1" name="customRadio" class="custom-control-input">
                                       <label class="custom-control-label" for="customRadio1">Would you like to pay by Cash on Delivery?</label>
                                    </div>
                                    <p>Vestibulum semper accumsan nisi, at blandit tortor maxi'mus in phasellus malesuada sodales odio, at dapibus libero malesuada quis.</p>
                                    <button type="button" data-toggle="collapse" data-target="#collapsefour" aria-expanded="false" aria-controls="collapsefour" class="btn btn-secondary mb-2 btn-lg">NEXT</button>
                                 </form>
                              </div>
                           </div>
                        </div>
                        <div class="card" style="display: none;">
                           <div class="card-header" id="headingThree">
                              <h5 class="mb-0">
                                 <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapsefour" aria-expanded="false" aria-controls="collapsefour">
                                 <span class="number">2</span> Order Complete
                                 </button>
                              </h5>
                           </div>
                           <div id="collapsefour" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                              <div class="card-body">
                                 <div class="text-center">
                                    <div class="col-lg-10 col-md-10 mx-auto order-done">
                                       <i class="mdi mdi-check-circle-outline text-secondary"></i>
                                       <h4 class="text-success">Congrats! Your Order has been Accepted..</h4>
                                       <p>
                                          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque lobortis tincidunt est, et euismod purus suscipit quis. Etiam euismod ornare elementum. Sed ex est, Sed ex est, consectetur eget consectetur, Lorem ipsum dolor sit amet...
                                       </p>
                                    </div>
                                    <div class="text-center">
                                       <a href="shop.php"><button type="submit" class="btn btn-secondary mb-2 btn-lg">Return to store</button></a>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="card" style="display:none;">
                     <h5 class="card-header">My Cart <span class="text-secondary float-right">(5 item)</span></h5>
                     <div class="card-body pt-0 pr-0 pl-0 pb-0">
                        <div class="cart-list-product">
                           <a class="float-right remove-cart" href="#"><i class="mdi mdi-close"></i></a>
                           <img class="img-fluid" src="img/item/11.jpg" alt="">
                           <span class="badge badge-success">50% OFF</span>
                           <h5><a href="#">Product Title Here</a></h5>
                           <h6><strong><span class="mdi mdi-approval"></span> Available in</strong> - 500 gm</h6>
                           <p class="offer-price mb-0">$450.99 <i class="mdi mdi-tag-outline"></i> <span class="regular-price">$800.99</span></p>
                        </div>
                        <div class="cart-list-product">
                           <a class="float-right remove-cart" href="#"><i class="mdi mdi-close"></i></a>
                           <img class="img-fluid" src="img/item/1.jpg" alt="">
                           <span class="badge badge-success">50% OFF</span>
                           <h5><a href="#">Product Title Here</a></h5>
                           <h6><strong><span class="mdi mdi-approval"></span> Available in</strong> - 500 gm</h6>
                           <p class="offer-price mb-0">$450.99 <i class="mdi mdi-tag-outline"></i> <span class="regular-price">$800.99</span></p>
                        </div>
                        <div class="cart-list-product">
                           <a class="float-right remove-cart" href="#"><i class="mdi mdi-close"></i></a>
                           <img class="img-fluid" src="img/item/2.jpg" alt="">
                           <span class="badge badge-success">50% OFF</span>
                           <h5><a href="#">Product Title Here</a></h5>
                           <h6><strong><span class="mdi mdi-approval"></span> Available in</strong> - 500 gm</h6>
                           <p class="offer-price mb-0">$450.99 <i class="mdi mdi-tag-outline"></i> <span class="regular-price">$800.99</span></p>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
        
      </section>

<section class="section-padding bg-white border-top">

   <div class="container">

      <div class="row">

         <div class="col-lg-4 col-sm-6">

            <div class="feature-box"> <i class="mdi mdi-truck-fast"></i>

               <h6>Free & Next Day Delivery</h6>

               <p>Lorem ipsum dolor sit amet, cons...</p>

            </div>

         </div>

         <div class="col-lg-4 col-sm-6">

            <div class="feature-box"> <i class="mdi mdi-basket"></i>

               <h6>100% Satisfaction Guarantee</h6>

               <p>Rorem Ipsum Dolor sit amet, cons...</p>

            </div>

         </div>

         <div class="col-lg-4 col-sm-6">

            <div class="feature-box"> <i class="mdi mdi-tag-heart"></i>

               <h6>Great Daily Deals Discount</h6>

               <p>Sorem Ipsum Dolor sit amet, Cons...</p>

            </div>

         </div>

      </div>

   </div>

</section>

<?php //include( 'footer.php') ?>
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js">-->
<script>
	
 //        $(document).ready(function(){
 // alert('hifdvfd');
 //  });
 function addtocart(pid){
 	var pidd=pid;
 	//alert(pidd);
 	$.ajax({

          type: 'get',

         // url: 'http://localhost/blog/public/addtocart',
           url:"{{url('/addtocart')}}/"+pidd,

          //data: {'pid':pidd},  

          success : function(data){

            // $('#category'+value).remove()

             //console.log(data);
             //alert(data);

          }

        });
 }
	</script>
	@endsection