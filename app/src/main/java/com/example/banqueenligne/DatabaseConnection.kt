package com.example.banqueenligne

import java.sql.DriverManager
import java.sql.SQLException
import java.sql.Statement

class DatabaseConnection {

    var url : String = "jdbc:mysql://concentre.o2switch.net:3306/yuqs8475_onlineBank"
    var user  : String = "yuqs8475_onlineBank"
    var pass : String = "Lego2014!"


    fun connexionSQLBDD(): Statement? {
        return try {
            Class.forName("com.mysql.jdbc.Driver")
            val conn = DriverManager.getConnection(url,user,pass)
            conn.createStatement()
        } catch (e: ClassNotFoundException) {
            e.printStackTrace()
            null
        } catch (e: SQLException) {
            e.printStackTrace()
            null
        }
    }

}