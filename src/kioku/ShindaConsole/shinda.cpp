#include <algorithm>
#include <utility>
#include "make.h"

using namespace std;

int const COUNT_COMMANDS = 1;

bool
in_array(string const &value, string array[])
{
    for (int i = 0; i < COUNT_COMMANDS; i++)
        if (array[i] == value)
            return true;

    return false;
}

void
create_object_for_command(string const &create)
{
    string name;
    string output = create;
    transform(output.begin(), output.end(), output.begin(), ::tolower);
    Make make;

    if ("controller" == output)
    {
        cout << "Okay, you choosed {controller}\n" << "Please, tell me name for controller: " << endl;
        cin >> name;

        make.make_controller(name);
    }
    else if ("middleware" == output)
    {
        cout << "Okay, you said to me{middleware}\n" << "Please, tell me name for middleware: " << endl;
        cin >> name;

        make.make_middleware(name);
    }
    else if ("entity" == output)
    {
        string table_name;

        cout << "Tell me name for table please:" << endl;
        cin >> table_name;

        cout << "Okay, you said to me {entity}\n" << "Please, tell me name for entity: " << endl;
        cin >> name;

        make.make_entity(name, table_name);
    }
    else if ("resource" == output)
    {
        string route;
        string controller;
        string entity;
        char views;
        string type;
        string file_route_name;

        cout << "Okay, you said to me {resource controller}. \n" << "Please, tell me name for route (with /): " << endl;
        cin >> route;

        cout << "Okay, tell me name for route files: (with type: (for example shinda.php)";
        cin >> file_route_name;

        cout << "Please, tell me name for controller" << endl;
        cin >> controller;

        cout << "Do you wanna generate views for this route: y/n" << endl;
        cin >> views;

        if ('y' == views)
        {
            cout << "Tell me type for views files: (example: .html) " << endl;
            cin >> type;

            cout << "Tell me name for entity: (with \\) " << endl;
            cin >> entity;

            make.generate_views_for_resource(route, type);
        }

        make.make_resource(route, controller, views, type, entity, file_route_name);
    }
    else
        cout << "Sorry, you entered incorrect name:" << name << endl;
}

void
after_enter_command(string const &value)
{
    if ("make" == value)
    {
        string *controller_name = new string;

        cout << "Okay, you said to me {make}.\n" << "Please, tell me name what you want create: " << endl;
        cout << "     controller \n" << "     middleware \n" << "     entity \n" << "     resource \n" << endl;
        cin >> *controller_name;

        create_object_for_command(*controller_name);

        delete controller_name;
    }
    else
        cout << "Incorrect name for command, sorry: " << value << endl;
}

int
main()
{
    string commands[COUNT_COMMANDS] = {
        "make"
    };

    string *command_output = new string;

    cout << "Hello, how are you? Okay, tell me what do you want do with me:" << endl;

    for (int i = 0; i < COUNT_COMMANDS; i++)
        cout << "--------   " << commands[i] << endl;

    cin >> *command_output;

    if (in_array(*command_output, commands))
        after_enter_command(*command_output);
    else
        cout << "Sorry, i cant do it because you said to me incorrect name: " << *command_output << endl;

    delete command_output;

    return 0;
}