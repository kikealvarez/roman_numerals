# roman_numerals
Tool for handling roman numerals

Generate roman numbers from integer numbers or parse roman numbers to integer numbers.
    It works for numbers between 1 and 3999
 
    Parameters:
        action = [ "generate" | "parse" ] 
        input =  if action is "generate" input must be an integer between 1 and 3999
                 if action is "parse" input must be a string representing a roman number between 1 and 3999
    Output: A formatted JSON response
 
    Examples of use:
        Input: http://localhost/roman/?action=generate&input=400
        Output: {"code":1,"status":200,"message":"Success","result":"CD"}
 
        Input: http://localhost/roman/?action=parse&input=XVI
        Output: {"code":1,"status":200,"message":"Success","result":16}
    PHP/5.5.12
    History: 27/08/2015 - Created
