package com.example.banqueenligne


import android.graphics.Color
import android.os.Bundle
import android.view.View
import android.widget.TextView
import androidx.appcompat.app.AppCompatActivity



class VersCompte : AppCompatActivity(){
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.vers_compte);
        //actionbar
        val actionbar = supportActionBar
        //set actionbar title
        actionbar!!.title = "Vers le compte"
        //set back button
        actionbar.setDisplayHomeAsUpEnabled(true)
    }

    override fun onSupportNavigateUp(): Boolean {
        onBackPressed()
        return true
    }

    fun toggleMesComptes(view : View?){
        val rl1 = findViewById<View>(R.id.mesComptes)
        rl1.visibility = View.VISIBLE

        val rl2 = findViewById<View>(R.id.comptesTiers)
        rl2.visibility = View.GONE

        val rl3 = findViewById<TextView>(R.id.displayMesComptes)
        rl3.setTextColor(Color.parseColor("red"))

        val rl4 = findViewById<TextView>(R.id.displayComptesTiers)
        rl4.setTextColor(Color.parseColor("black"))

        val rl5 = findViewById<TextView>(R.id.displayMesComptes)
        rl5.setBackgroundResource(R.drawable.border_bottom_red)

        val rl6 = findViewById<TextView>(R.id.displayComptesTiers)
        rl6.setBackgroundResource(R.drawable.border_bottom_black)




    }

    fun toggleComptesTiers(view : View?){
        val rl1 = findViewById<View>(R.id.mesComptes)
        rl1.visibility = View.GONE

        val rl2 = findViewById<View>(R.id.comptesTiers)
        rl2.visibility = View.VISIBLE

        val rl3 = findViewById<TextView>(R.id.displayComptesTiers)
        rl3.setTextColor(Color.parseColor("red"))

        val rl4 = findViewById<TextView>(R.id.displayMesComptes)
        rl4.setTextColor(Color.parseColor("black"))

        val rl5 = findViewById<TextView>(R.id.displayMesComptes)
        rl5.setBackgroundResource(R.drawable.border_bottom_black)

        val rl6 = findViewById<TextView>(R.id.displayComptesTiers)
        rl6.setBackgroundResource(R.drawable.border_bottom_red)
    }

}