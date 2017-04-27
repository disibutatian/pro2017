#include <iostream>
#include <vector>
#include <stdio.h>
#include <algorithm>

#define MAX 2147483647


bool GpsCompare(const double& pfirst,const double& psecond) 
{
     return pfirst <= psecond;
}

int main()
{
    std::vector<double> vec;
    for(double i = 0;i < 17;i++)
        vec.push_back(MAX );

    for(std::vector<double>::iterator iter = vec.begin();iter != vec.end();iter++)
    {
        //std::cout << iter << std::endl;
        std::cout << *iter << std::endl;
    }
    //std::vector<double>::iterator iter = vec.end();
    //iter = (iter - 1);
    //std::cout <<"321  " << (*iter) << std::endl;
    sort(vec.begin(), vec.end(), GpsCompare);

    for(std::vector<double>::iterator iter = vec.begin();iter != vec.end();iter++)
    {
        std::cout << *iter << std::endl;
    }
    return 0;
}
